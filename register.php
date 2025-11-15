<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <title>Registreren - GlowCare Webshop</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>
    <nav class="navbar">
      <a href="home.html" class="logo">GlowCare</a>

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
        <a href="login.html" class="login-btn">Inloggen</a>
        <a href="winkelwagen.html" class="cart-btn" aria-label="Winkelwagen"
          >🛒</a
        >
      </div>
    </nav>

    <main class="main-register-wrapper">
      <section class="register-section">
        <h2>Account aanmaken</h2>
        <form class="register-form" action="#" method="POST">
          <label for="firstname">Voornaam</label>
          <input type="text" id="firstname" name="firstname" required />

          <label for="lastname">Achternaam</label>
          <input type="text" id="lastname" name="lastname" required />

          <label for="email">E-mailadres</label>
          <input type="email" id="email" name="email" required />

          <label for="username">Gebruikersnaam</label>
          <input type="text" id="username" name="username" required />

          <label for="password">Wachtwoord</label>
          <input type="password" id="password" name="password" required />

          <label for="confirm-password">Bevestig wachtwoord</label>
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
            required
          />

          <button type="submit">Registreren</button>

          <p class="login-link">
            Heb je al een account?
            <a href="login.html">Log hier in</a>
          </p>
        </form>
      </section>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
