<?php
session_start();
require_once(__DIR__ . "/classes/Database.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $current = $_POST['current-password'];
        $new = $_POST['new-password'];
        $confirm = $_POST['confirm-password'];

        $conn = Database::getConnection();

        // 1️⃣ Huidig wachtwoord ophalen
        $stmt = $conn->prepare("
            SELECT Password FROM passwords
            WHERE User_ID = :id AND Is_Current = 1
        ");
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("Gebruiker niet gevonden.");
        }

        // 2️⃣ Check huidig wachtwoord
        if (!password_verify($current, $user['Password'])) {
            throw new Exception("Huidig wachtwoord is onjuist.");
        }

        // 3️⃣ Check nieuw wachtwoord
        if (strlen($new) < 4) {
            throw new Exception("Nieuw wachtwoord moet minstens 4 tekens bevatten.");
        }

        if ($new !== $confirm) {
            throw new Exception("Nieuwe wachtwoorden komen niet overeen.");
        }

        // 4️⃣ Oude wachtwoord ongeldig maken
        $update = $conn->prepare("UPDATE passwords SET Is_Current = 0 WHERE User_ID = :id");
        $update->bindValue(':id', $_SESSION['user_id']);
        $update->execute();

        // 5️⃣ Nieuw wachtwoord opslaan
        $options = ['cost' => 14];
        $hashed = password_hash($new, PASSWORD_DEFAULT, $options);

        $insert = $conn->prepare("
            INSERT INTO passwords (Password, User_ID, Is_Current)
            VALUES (:pass, :id, 1)
        ");
        $insert->bindValue(':pass', $hashed);
        $insert->bindValue(':id', $_SESSION['user_id']);
        $insert->execute();

        // ✅ Redirect met succesbericht
        header("Location: account.php?success=1");
        exit;
    }
} catch (Exception $e) {
    $errorMessage = urlencode($e->getMessage());
    header("Location: account.php?error=$errorMessage");
    exit;
}
?>
