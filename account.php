<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mijn Account - GlowCare Webshop</title>
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

    <main class="account-container">
      <section class="account-card">
        <h2>Welkom, <span id="user-name">Gebruiker</span> 👋</h2>

        <div class="balance-box">
          <h3>Beschikbaar saldo:</h3>
          <p class="balance">€ 10.000</p>
        </div>

        <div class="account-info">
          <h3>Mijn gegevens</h3>
          <ul>
            <li><strong>Gebruikersnaam:</strong> <span>Gebruiker123</span></li>
            <li><strong>E-mail:</strong> <span>gebruiker@example.com</span></li>
          </ul>
        </div>

        <div class="password-section">
          <h3>Wachtwoord wijzigen</h3>
          <form
            class="password-form"
            action="update_password.php"
            method="POST"
          >
            <label for="current-password">Huidig wachtwoord</label>
            <input
              type="password"
              id="current-password"
              name="current-password"
              required
            />

            <label for="new-password">Nieuw wachtwoord</label>
            <input
              type="password"
              id="new-password"
              name="new-password"
              required
            />

            <label for="confirm-password">Bevestig nieuw wachtwoord</label>
            <input
              type="password"
              id="confirm-password"
              name="confirm-password"
              required
            />

            <button type="submit">Wachtwoord bijwerken</button>
          </form>
        </div>

        <div class="orders-section">
          <h3>Mijn bestellingen</h3>

          <table class="orders-table">
            <thead>
              <tr>
                <th>Datum</th>
                <th>Producten</th>
                <th>Totaal</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>27 okt 2025</td>
                <td>3 stuks</td>
                <td>€ 58,90</td>
                <td class="status-paid">Betaald</td>
              </tr>
              <tr>
                <td>24 okt 2025</td>
                <td>1 stuk</td>
                <td>€ 24,95</td>
                <td class="status-paid">Betaald</td>
              </tr>
              <tr>
                <td>20 okt 2025</td>
                <td>2 stuks</td>
                <td>€ 42,50</td>
                <td class="status-pending">In behandeling</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
