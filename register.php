<?php
session_start();
require_once(__DIR__ . "/classes/User.php");
require_once(__DIR__ . "/nav.inc.php");

// Admins mogen hier niet komen
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit;
}

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // === Validatie ===
        $email = trim($_POST['email']);
        $emailRepeat = trim($_POST['username']);
        $password = $_POST['password'];
        $passwordRepeat = $_POST['confirm-password'];

        if ($email !== $emailRepeat) {
            throw new Exception("De e-mailadressen komen niet overeen.");
        }

        if ($password !== $passwordRepeat) {
            throw new Exception("De wachtwoorden komen niet overeen.");
        }

        // === Nieuwe gebruiker aanmaken ===
        $user = new User();
        $user->setFirstName($_POST['firstname']);
        $user->setLastName($_POST['lastname']);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->save();

        $success = "Account succesvol aangemaakt! Je kunt nu inloggen.";
    } 
    catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <title>Registreren - GlowCare Webshop</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>

    <main class="main-register-wrapper">
      <section class="register-section">
        <h2>Account aanmaken</h2>

        <!-- Feedback -->
        <?php if($error): ?>
          <div class="feedback error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif($success): ?>
          <div class="feedback success"><?php echo htmlspecialchars($success); ?></div>
          <meta http-equiv="refresh" content="2;url=login.php" />
        <?php endif; ?>

        <form class="register-form" method="POST" action="">
          <label for="firstname">Voornaam</label>
          <input type="text" id="firstname" name="firstname" required />

          <label for="lastname">Achternaam</label>
          <input type="text" id="lastname" name="lastname" required />

          <label for="email">E-mailadres</label>
          <input type="email" id="email" name="email" required />

          <label for="username">Herhaal e-mailadres</label>
          <input type="email" id="username" name="username" required />

          <label for="password">Wachtwoord</label>
          <input type="password" id="password" name="password" required />

          <label for="confirm-password">Bevestig wachtwoord</label>
          <input type="password" id="confirm-password" name="confirm-password" required />

          <button type="submit">Registreren</button>

          <p class="login-link">
            Heb je al een account?
            <a href="login.php">Log hier in</a>
          </p>
        </form>
      </section>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
