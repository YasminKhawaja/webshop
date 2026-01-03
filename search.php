<?php
require_once(__DIR__ . "/classes/Database.php");
require_once(__DIR__ . "/classes/Product.php");
require_once(__DIR__ . "/classes/Cart.php");
require_once("nav.inc.php");

// Zoekterm ophalen en normaliseren
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    // Geen zoekterm: terug naar home of toon een melding
    header("Location: home.php");
    exit;
}

// Paginatie-instellingen
$perPage = 6;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Totaal aantal producten + producten voor deze pagina
$totalProducts = Product::countSearch($q);
$products = Product::search($q, $perPage, $offset);
$totalPages = max(1, (int)ceil($totalProducts / $perPage));
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Zoeken naar "<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>" - GlowCare</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body>

<section class="product-overview">
  <h2>Zoekresultaten voor: "<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>"</h2>

  <?php if ($totalProducts === 0): ?>
    <p>Geen producten gevonden.</p>
  <?php else: ?>
    <div class="products">
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <a href="detailpagina.php?id=<?= htmlspecialchars($product['Product_ID']); ?>" class="product-card-link">
            <img src="images/<?= htmlspecialchars($product['Image']); ?>" alt="<?= htmlspecialchars($product['Product_Name']); ?>">
            <h3><?= htmlspecialchars($product['Product_Name']); ?></h3>
            <p><?= htmlspecialchars($product['Description']); ?></p>
            <span class="price">€<?= number_format($product['Price'], 2, ',', '.'); ?></span>
          </a>
          <form action="winkelwagen.php" method="POST">
            <input type="hidden" name="id" value="<?= $product['Product_ID']; ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($product['Product_Name']); ?>">
            <input type="hidden" name="price" value="<?= $product['Price']; ?>">
            <button type="submit">Toevoegen aan winkelwagen</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Paginatie -->
    <div class="pagination">
      <?php
      $queryParams = $_GET;
      for ($i = 1; $i <= $totalPages; $i++):
        $queryParams['page'] = $i;
        $queryStr = http_build_query($queryParams);
      ?>
        <a href="?<?= htmlspecialchars($queryStr, ENT_QUOTES, 'UTF-8'); ?>"
           class="<?= $i === $page ? 'active' : ''; ?>">
          <?= $i; ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</section>

<footer>
  <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
</footer>

</body>
</html>
