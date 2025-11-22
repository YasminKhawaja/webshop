<?php
include_once(__DIR__ . "/Database.php");

class User {
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $password;

    // === SETTERS ===
    public function setFirstName($firstName) {
        if (empty($firstName) || strlen($firstName) < 2) {
            throw new Exception("Voornaam moet minstens 2 letters bevatten.");
        }
        $this->firstName = ucfirst(htmlspecialchars($firstName));
    }

    public function setLastName($lastName) {
        if (empty($lastName) || strlen($lastName) < 2) {
            throw new Exception("Achternaam moet minstens 2 letters bevatten.");
        }
        $this->lastName = ucfirst(htmlspecialchars($lastName));
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres.");
        }
        $this->email = strtolower(htmlspecialchars($email));
    }

    public function setPassword($password) {
        if (strlen($password) < 4) {
            throw new Exception("Wachtwoord moet minstens 4 tekens bevatten.");
        }
        $options = ['cost' => 14];
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);
    }

    // === GETTERS ===
    public function getFirstName() { return $this->firstName; }
    public function getLastName() { return $this->lastName; }
    public function getEmail() { return $this->email; }

    // === LOGIN ===
    public static function login($email, $password) {
        $conn = Database::getConnection();

        $statement = $conn->prepare("
            SELECT u.User_ID, u.First_Name, p.Password
            FROM users u
            JOIN passwords p ON u.User_ID = p.User_ID
            WHERE u.Email = :email AND p.Is_Current = 1
        ");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            session_start();
            $_SESSION['user_id'] = $user['User_ID'];
            $_SESSION['first_name'] = $user['First_Name'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'user';
            return true;
        } else {
            return false;
        }
    }

    // === REGISTER ===
    public function register() {
        $conn = Database::getConnection();

        // 1. Check of e-mail al bestaat
        $check = $conn->prepare("SELECT * FROM users WHERE Email = :email");
        $check->bindValue(":email", $this->email);
        $check->execute();

        if ($check->rowCount() > 0) {
            throw new Exception("Er bestaat al een account met dit e-mailadres.");
        }

        // 2. Voeg gebruiker toe
        $statement = $conn->prepare("
            INSERT INTO users (First_Name, Last_Name, Email, Account_Balance)
            VALUES (:firstName, :lastName, :email, 10000)
        ");
        $statement->bindValue(":firstName", $this->firstName);
        $statement->bindValue(":lastName", $this->lastName);
        $statement->bindValue(":email", $this->email);
        $statement->execute();

        // 3. Krijg User_ID en voeg wachtwoord toe
        $userId = $conn->lastInsertId();

        $passStmt = $conn->prepare("
            INSERT INTO passwords (Password, User_ID, Is_Current)
            VALUES (:password, :userId, 1)
        ");
        $passStmt->bindValue(":password", $this->password);
        $passStmt->bindValue(":userId", $userId);
        $passStmt->execute();

        return true;
    }
}
