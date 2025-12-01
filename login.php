<?php
include_once(__DIR__ . "/classes/Database.php");
include_once(__DIR__ . "/classes/User.php");
include_once("nav.inc.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username']; 
    $password = $_POST['password'];

    if (User::login($email, $password)) {
        header("Location: account.php");
        exit;
    } else {
        $error = "Ongeldig e-mailadres of wachtwoord.";
    }
}

?><!DOCTYPE html>
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
        <form class="login-form" action="login.php" method="POST">
          <label for="username">Gebruikersnaam</label>
          <input type="text" id="username" name="username" required />

          <label for="password">Wachtwoord</label>
          <input type="password" id="password" name="password" required />

          <a href="" class="forgot-password"
            >Wachtwoord vergeten?</a
          >

        
          <?php if (!empty($error)): ?>
              <p style="color: red; text-align: center;"><?php echo $error; ?></p>
          <?php endif; ?>

          <button type="submit">Inloggen</button>

          <a href="admin-login.php" class="forgot-password"
            >Inloggen als Admin</a
          >

          <span class="anders">Of</span>

          <a href="register.php" class="register-btn">Registreren</a>
        </form>
      </section>
    </main>
  </body>
</html>
