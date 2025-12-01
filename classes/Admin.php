<?php
include_once(__DIR__ . "/Database.php");

class Admin {
    private string $email;
    private string $password;

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres.");
        }
        $this->email = htmlspecialchars($email);
    }

    public function setPassword($password) {
        if (strlen($password) < 6) {
            throw new Exception("Wachtwoord moet minstens 6 tekens bevatten.");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 14]);
    }

    // === LOGIN ===
    public static function login($email, $password) {
        $conn = Database::getConnection();

        $statement = $conn->prepare("
            SELECT a.Admin_ID, a.First_Name, p.Password
            FROM admins a
            JOIN admin_passwords p ON a.Admin_ID = p.Admin_ID
            WHERE a.Email = :email AND p.Is_Current = 1
        ");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $admin = $statement->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['Password'])) {
            session_start();
            $_SESSION['admin_id'] = $admin['Admin_ID'];
            $_SESSION['first_name'] = $admin['First_Name'];
            $_SESSION['role'] = 'admin';
            return true;
        } else {
            return false;
        }
    }
}
