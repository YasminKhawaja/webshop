<?php
session_start();
require_once(__DIR__ . "/classes/User.php");
require_once("nav.inc.php");

// Als een gebruiker of admin al is ingelogd → direct doorsturen
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'user') {
        header("Location: account.php");
        exit;
    } elseif ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit;
    }
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username']); 
    $password = $_POST['password'];

    try {
        if (User::login($email, $password)) {
            header("Location: account.php");
            exit;
        } else {
            $error = "Ongeldig e-mailadres of wachtwoord.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <title>Login - GlowCare Webshop</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>

    <main class="main-login-wrapper">
      <section class="login-section">
        <h2>Inloggen</h2>

        <?php if(!empty($error)): ?>
          <div class="feedback error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="">
          <label for="username">E-mailadres</label>
          <input type="email" id="username" name="username" required />

          <label for="password">Wachtwoord</label>
          <input type="password" id="password" name="password" required />

          <a href="#" class="forgot-password">Wachtwoord vergeten?</a>

          <button type="submit">Inloggen</button>

          <a href="admin-login.php" class="forgot-password">Inloggen als Admin</a>

          <span class="anders">Of</span>

          <a href="register.php" class="register-btn">Registreren</a>
        </form>
      </section>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
