<?php
session_start();
require_once(__DIR__ . "/classes/Admin.php");
require_once("nav.inc.php");

// Als een user al ingelogd is, mag hij hier niet komen
if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    header("Location: account.php");
    exit;
}

// Als admin al ingelogd is → dashboard
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin-dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        if (Admin::login($email, $password)) {
            header("Location: admin-dashboard.php");
            exit();
        } else {
            $error = "Ongeldige logingegevens.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - GlowCare</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <main class="main-login-wrapper">
        <section class="login-section">
            <h2>Admin Inloggen</h2>

            <?php if(!empty($error)): ?>
                <div class="feedback error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Inloggen</button>
            </form>

            <div style="text-align:center; margin-top:1em;">
                <a href="login.php" class="forgot-password">Terug naar gebruikerslogin</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
