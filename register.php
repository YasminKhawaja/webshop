<?php
include_once(__DIR__ . "/classes/User.php");
include_once("nav.inc.php");

if(!empty($_POST)){
    try {
        // Controle op dubbele e-mail en wachtwoordmatch
        if($_POST['email'] !== $_POST['username']){
            throw new Exception("De e-mailadressen komen niet overeen.");
        }

        if($_POST['password'] !== $_POST['confirm-password']){
            throw new Exception("De wachtwoorden komen niet overeen.");
        }

        // Nieuwe gebruiker aanmaken
        $user = new User();
        $user->setFirstName($_POST['firstname']);
        $user->setLastName($_POST['lastname']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->register();

        echo "<script>alert('Account succesvol aangemaakt! Je kunt nu inloggen.'); window.location='login.php';</script>";

    } catch(Exception $e){
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

        <?php if(isset($error)): ?>
          <div style="color: red; margin-bottom: 1em; text-align:center;">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form class="register-form" action="" method="POST">
          <label for="firstname">Voornaam</label>
          <input type="text" id="firstname" name="firstname" required />

          <label for="lastname">Achternaam</label>
          <input type="text" id="lastname" name="lastname" required />

          <label for="email">E-mailadres</label>
          <input type="email" id="email" name="email" required />

          <label for="username">Herhaal e-mailadres</label>
          <input type="text" id="username" name="username" required />

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
