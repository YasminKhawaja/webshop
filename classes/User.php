<?php
require_once(__DIR__ . '/Person.php');
require_once(__DIR__ . '/Database.php');
include_once(__DIR__ . "/../traits/passwordTrait.php");

class User extends Person {

    private float $accountBalance = 10000.00; // standaard startkrediet

    // === REGISTREREN ===
    public function save(): bool {
        $conn = Database::getConnection();

        // --- Controleer of e-mail al bestaat ---
        $check = $conn->prepare("SELECT Email FROM users WHERE Email = :email");
        $check->bindValue(':email', $this->email);
        $check->execute();

        if ($check->rowCount() > 0) {
            throw new Exception("Er bestaat al een account met dit e-mailadres.");
        }

        // --- Nieuwe gebruiker opslaan ---
        $stmt = $conn->prepare("
            INSERT INTO users (First_Name, Last_Name, Email, Account_Balance)
            VALUES (:firstName, :lastName, :email, :balance)
        ");
        $stmt->bindValue(':firstName', $this->firstName);
        $stmt->bindValue(':lastName', $this->lastName);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':balance', $this->accountBalance);
        $stmt->execute();

        $userId = $conn->lastInsertId();

        // --- Wachtwoord opslaan in aparte tabel ---
        $stmtPass = $conn->prepare("
            INSERT INTO passwords (Password, User_ID, Is_Current)
            VALUES (:password, :userId, 1)
        ");
        $stmtPass->bindValue(':password', $this->getPassword());
        $stmtPass->bindValue(':userId', $userId);
        $stmtPass->execute();

        return true;
    }

    // === LOGIN ===
    public static function login(string $email, string $password): bool {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            SELECT u.User_ID, u.First_Name, u.Account_Balance, p.Password
            FROM users u
            JOIN passwords p ON u.User_ID = p.User_ID
            WHERE u.Email = :email AND p.Is_Current = 1
        ");
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && self::verifyPassword($password, $user['Password'])) {
            session_start();
            $_SESSION['user_id'] = $user['User_ID'];
            $_SESSION['first_name'] = $user['First_Name'];
            $_SESSION['email'] = $email;
            $_SESSION['balance'] = $user['Account_Balance'];
            $_SESSION['role'] = 'user';
            return true;
        }

        return false;
    }

    // === UITLOGGEN ===
    public static function logout(): void {
        session_start();
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }

        // === USER DETAILS OPHALEN ===
    public static function getById(int $userId): ?array {
        $conn = Database::getConnection();

        $statement = $conn->prepare("
            SELECT User_ID, First_Name, Last_Name, Email, Account_Balance
            FROM users
            WHERE User_ID = :id
        ");
        $statement->bindValue(":id", $userId, PDO::PARAM_INT);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

        // === SALDO AANPASSEN ===
    public static function updateBalance(int $userId, float $newBalance): bool {
        $conn = Database::getConnection();
        $statement = $conn->prepare("UPDATE users SET Account_Balance = :balance WHERE User_ID = :id");
        $statement->bindValue(':balance', $newBalance);
        $statement->bindValue(':id', $userId, PDO::PARAM_INT);
        return $statement->execute();
    }

    // === CHECK SALDO ===
    public static function hasEnoughBalance(int $userId, float $amount): bool {
        $conn = Database::getConnection();
        $statement = $conn->prepare("SELECT Account_Balance FROM users WHERE User_ID = :id");
        $statement->bindValue(':id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $balance = $statement->fetchColumn();

        return $balance !== false && $balance >= $amount;
    }

    // === VERMINDER SALDO ===
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


}
