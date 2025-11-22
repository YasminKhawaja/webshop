<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <title>Login - GlowCare Webshop</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>
    <nav class="navbar">
      <a href="home.php" class="logo">GlowCare</a>

      <form class="search-form" action="/search" method="GET">
        <input
          type="search"
          name="q"
          placeholder="Waar ben je naar op zoek?"
          aria-label="Zoeken"
          required
        />
        <button type="submit" aria-label="Zoeken">Zoek</button>
      </form>

      <div class="nav-actions">
        <a href="login.php" class="login-btn">Inloggen</a>
        <a href="winkelwagen.php" class="cart-btn" aria-label="Winkelwagen"
          >🛒</a
        >
      </div>
    </nav>

    <main class="main-login-wrapper">
      <section class="login-section">
        <h2>Inloggen</h2>
        <form class="login-form" action="#" method="POST">
          <label for="username">Gebruikersnaam</label>
          <input type="text" id="username" name="username" required />

          <label for="password">Wachtwoord</label>
          <input type="password" id="password" name="password" required />

          <a href="password-reset.php" class="forgot-password"
            >Wachtwoord vergeten?</a
          >

          <button type="submit">Inloggen</button>

          <span class="anders">Of</span>

          <a href="register.php" class="register-btn">Registreren</a>
        </form>
      </section>
    </main>
  </body>
</html>
