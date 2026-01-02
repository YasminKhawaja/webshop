<?php
session_start();
require_once("nav.inc.php");

// --- Toegangscontrole ---
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mijn Account - GlowCare Webshop</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>

    <main class="account-container">
      <section class="account-card">
        <h2>Welkom, <span id="user-name"><?= htmlspecialchars($_SESSION['first_name']); ?></span> 👋</h2>

        <div class="balance-box">
          <h3>Beschikbaar saldo:</h3>
          <?php
include_once(__DIR__ . "/classes/User.php");
$user = User::getById($_SESSION['user_id']);
$orders = User::getOrders($_SESSION['user_id']);

?>
<p class="balance">€ <?= number_format($user['Account_Balance'], 2, ',', '.'); ?></p>

        </div>

        <div class="account-info">
          <h3>Mijn gegevens</h3>
          <ul>
            <li><strong>Voornaam:</strong> <?= htmlspecialchars($_SESSION['first_name']); ?></li>
            <li><strong>E-mail:</strong> <?= htmlspecialchars($_SESSION['email']); ?></li>
          </ul>
        </div>

        <?php if (isset($_GET['success'])): ?>
  <div style="color: green; text-align: center; margin-bottom: 1em;">
    Wachtwoord succesvol gewijzigd.
  </div>
<?php elseif (isset($_GET['error'])): ?>
  <div style="color: red; text-align: center; margin-bottom: 1em;">
    <?php echo htmlspecialchars($_GET['error']); ?>
  </div>
<?php endif; ?>

        <div class="password-section">
          <h3>Wachtwoord wijzigen</h3>
          <form class="password-form" action="update_password.php" method="POST">
            <label for="current-password">Huidig wachtwoord</label>
            <input type="password" id="current-password" name="current-password" required />

            <label for="new-password">Nieuw wachtwoord</label>
            <input type="password" id="new-password" name="new-password" required />

            <label for="confirm-password">Bevestig nieuw wachtwoord</label>
            <input type="password" id="confirm-password" name="confirm-password" required />

            <button type="submit">Wachtwoord bijwerken</button>
          </form>
        </div>

        <div class="orders-section">
  <h3>Mijn bestellingen</h3>

  <?php if (empty($orders)): ?>
    <p>Je hebt nog geen bestellingen geplaatst.</p>
  <?php else: ?>
    <table class="orders-table">
      <thead>
        <tr>
          <th>Datum</th>
          <th>Producten</th>
          <th>Totaal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?= date('d/m/Y', strtotime($order['Ordered_At'])); ?></td>
            <td><?= htmlspecialchars($order['ProductCount']); ?> stuks</td>
            <td>€ <?= number_format($order['Price'], 2, ',', '.'); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

      </section>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>