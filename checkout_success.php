<?php
session_start();
require_once("nav.inc.php");

// Controle: alleen toegankelijk na een betaling
if (!isset($_GET['amount'])) {
    header("Location: home.php");
    exit;
}

$amount = htmlspecialchars($_GET['amount']);
?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <title>Betaling Geslaagd - GlowCare</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>
    <main class="success-container">
      <section class="success-card">
        <h2>Betaling geslaagd!</h2>
        <p>Bedankt voor je aankoop, <?= htmlspecialchars($_SESSION['first_name'] ?? ''); ?>!</p>
        <p>Je hebt <strong>€<?= number_format((float)$amount, 2, ',', '.'); ?></strong> betaald.</p>

        <div class="success-links">
          <a href="account.php" class="btn">Bekijk mijn bestellingen</a>
          <a href="home.php" class="btn">Verder shoppen</a>
        </div>
      </section>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
