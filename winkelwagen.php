<?php
require_once("nav.inc.php");
require_once(__DIR__ . "/classes/Cart.php");
// session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // product toevoegen
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $price = (float)$_POST['price'];

    Cart::addProduct($id, $name, $price);
}

$cart = Cart::getCart();
$total = Cart::getTotal();
?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Winkelwagen - GlowCare</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>
    <main class="cart-container">
      <h2>🛒 Mijn Winkelwagen</h2>

      <?php if (empty($cart)): ?>
        <p>Je winkelwagen is leeg.</p>
        <a href="home.php" class="back-to-shop">Verder winkelen</a>
      <?php else: ?>
        <div class="cart-items">
          <?php foreach ($cart as $id => $item): ?>
            <div class="cart-item">
              <img src="images/default-product.jpg" alt="<?= htmlspecialchars($item['name']); ?>" />
              <div class="item-info">
                <h3><?= htmlspecialchars($item['name']); ?></h3>
                <p>Prijs: €<?= number_format($item['price'], 2, ',', '.'); ?></p>
                <p>Aantal: <?= $item['quantity']; ?></p>
              </div>
              <div class="item-subtotal">
                <p>Subtotaal: €<?= number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></p>
                <a href="remove_from_cart.php?id=<?= $id; ?>" class="remove-btn">Verwijderen</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="cart-summary">
          <h3>Totaaloverzicht</h3>
          <p><strong>Totaal:</strong> €<?= number_format($total, 2, ',', '.'); ?></p>
          <a href="checkout.php" class="checkout-btn">Afrekenen</a>
        </div>
      <?php endif; ?>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
