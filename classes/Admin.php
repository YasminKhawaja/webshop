<?php
require_once(__DIR__ . '/Person.php');
require_once(__DIR__ . '/Database.php');

class Admin extends Person {

    // === LOGIN ===
    public static function login(string $email, string $password): bool {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            SELECT a.Admin_ID, a.First_Name, p.Password
            FROM admins a
            JOIN admin_passwords p ON a.Admin_ID = p.Admin_ID
            WHERE a.Email = :email AND p.Is_Current = 1
        ");
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && self::verifyPassword($password, $admin['Password'])) {
            session_start();
            $_SESSION['admin_id'] = $admin['Admin_ID'];
            $_SESSION['first_name'] = $admin['First_Name'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'admin';
            return true;
        }

        return false;
    }

    // === UITLOGGEN ===
    public static function logout(): void {
        session_start();
        session_unset();
        session_destroy();
        header('Location: admin_login.php');
        exit();
    }

    // === (optioneel) OPSLAAN VAN NIEUWE ADMIN ===
    // Normaal gebeurt dit niet via registratieformulier
    public function save(): bool {
        $conn = Database::getConnection();

        $check = $conn->prepare("SELECT * FROM admins WHERE Email = :email");
        $check->bindValue(':email', $this->email);
        $check->execute();

        if ($check->rowCount() > 0) {
            throw new Exception("Er bestaat al een admin met dit e-mailadres.");
        }

        $stmt = $conn->prepare("
            INSERT INTO admins (First_Name, Last_Name, Email)
            VALUES (:firstName, :lastName, :email)
        ");
        $stmt->bindValue(':firstName', $this->firstName);
        $stmt->bindValue(':lastName', $this->lastName);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();

        $adminId = $conn->lastInsertId();

        $stmtPass = $conn->prepare("
            INSERT INTO admin_passwords (Password, Admin_ID, Is_Current)
            VALUES (:password, :adminId, 1)
        ");
        $stmtPass->bindValue(':password', $this->getPassword());
        $stmtPass->bindValue(':adminId', $adminId);
        $stmtPass->execute();

        return true;
    }
}
