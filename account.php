<?php
require_once(__DIR__ . "/Database.php");

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
        $this->firstName = ucfirst(htmlspecialchars(trim($firstName)));
    }

    public function setLastName($lastName) {
        if (empty($lastName) || strlen($lastName) < 2) {
            throw new Exception("Achternaam moet minstens 2 letters bevatten.");
        }
        $this->lastName = ucfirst(htmlspecialchars(trim($lastName)));
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres.");
        }
        $this->email = strtolower(htmlspecialchars(trim($email)));
    }

    public function setPassword($password) {
        if (strlen($password) < 6) {
            throw new Exception("Wachtwoord moet minstens 6 tekens bevatten.");
        }
        $options = ['cost' => 14];
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);
    }

    // === GETTERS ===
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getEmail(): string { return $this->email; }

    // === REGISTREREN ===
    public function register(): bool {
        $conn = Database::getConnection();

        // Controle of e-mailadres al bestaat
        $check = $conn->prepare("SELECT * FROM users WHERE Email = :email");
        $check->bindValue(":email", $this->email);
        $check->execute();
        if ($check->rowCount() > 0) {
            throw new Exception("Er bestaat al een account met dit e-mailadres.");
        }

        // Gebruiker toevoegen
        $statement = $conn->prepare("
            INSERT INTO users (First_Name, Last_Name, Email, Account_Balance)
            VALUES (:firstName, :lastName, :email, 10000.00)
        ");
        $statement->bindValue(":firstName", $this->firstName);
        $statement->bindValue(":lastName", $this->lastName);
        $statement->bindValue(":email", $this->email);
        $statement->execute();

        // Wachtwoord opslaan in aparte tabel
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

    // === LOGIN ===
    public static function login(string $email, string $password): bool {
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
        }
        return false;
    }

    // === GEBRUIKER OPHALEN OP ID ===
    public static function getById(int $userId): ?array {
        $conn = Database::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE User_ID = :id");
        $statement->bindValue(":id", $userId, PDO::PARAM_INT);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    // === SALDO UPDATEN ===
    public static function updateBalance(int $userId, float $newBalance): bool {
        $conn = Database::getConnection();
        $statement = $conn->prepare("UPDATE users SET Account_Balance = :balance WHERE User_ID = :id");
        $statement->bindValue(':balance', $newBalance);
        $statement->bindValue(':id', $userId, PDO::PARAM_INT);
        return $statement->execute();
    }

    // === CONTROLEREN OF ER GENOEG SALDO IS ===
    public static function hasEnoughBalance(int $userId, float $amount): bool {
        $conn = Database::getConnection();
        $statement = $conn->prepare("SELECT Account_Balance FROM users WHERE User_ID = :id");
        $statement->bindValue(':id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $balance = $statement->fetchColumn();
        return $balance !== false && $balance >= $amount;
    }

    // === SALDO AFHOUDEN ===
    public static function deductBalance(int $userId, float $amount): bool {
        $conn = Database::getConnection();
        $statement = $conn->prepare("
            UPDATE users 
            SET Account_Balance = Account_Balance - :amount 
            WHERE User_ID = :id AND Account_Balance >= :amount
        ");
        $statement->bindValue(':amount', $amount);
        $statement->bindValue(':id', $userId, PDO::PARAM_INT);
        return $statement->execute();
    }

    // === WACHTWOORD WIJZIGEN ===
    public static function updatePassword(int $userId, string $currentPassword, string $newPassword): bool {
        $conn = Database::getConnection();

        // Huidig wachtwoord ophalen
        $stmt = $conn->prepare("
            SELECT Password FROM passwords 
            WHERE User_ID = :id AND Is_Current = 1
        ");
        $stmt->bindValue(':id', $userId);
        $stmt->execute();
        $hash = $stmt->fetchColumn();

        if (!$hash || !password_verify($currentPassword, $hash)) {
            throw new Exception("Huidig wachtwoord is onjuist.");
        }

        // Oud wachtwoord deactiveren
        $conn->prepare("
            UPDATE passwords SET Is_Current = 0 WHERE User_ID = :id
        ")->execute([':id' => $userId]);

        // Nieuw wachtwoord opslaan
        $options = ['cost' => 14];
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT, $options);

        $insert = $conn->prepare("
            INSERT INTO passwords (Password, User_ID, Is_Current)
            VALUES (:password, :userId, 1)
        ");
        $insert->bindValue(':password', $newHash);
        $insert->bindValue(':userId', $userId);
        return $insert->execute();
    }
}
