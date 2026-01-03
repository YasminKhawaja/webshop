<?php
require_once(__DIR__ . "/classes/Database.php");
require_once(__DIR__ . "/classes/Product.php");
require_once(__DIR__ . "/classes/Cart.php");
require_once("nav.inc.php");

// --- PAGINATIE INSTELLEN ---
$perPage = 6;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// --- FILTERS OPHALEN ---
$filters = [
    'brand'        => isset($_GET['brand']) ? (array)$_GET['brand'] : [],
    'category'     => isset($_GET['category']) ? (array)$_GET['category'] : [],
    'producttype'  => isset($_GET['producttype']) ? (array)$_GET['producttype'] : [],
    'crueltyfree'  => isset($_GET['crueltyfree']) ? $_GET['crueltyfree'] : ''
];

// --- BEPALEN WELKE PRODUCTEN OPHALEN ---
$hasFilters = !empty($filters['brand']) || !empty($filters['category']) || 
              !empty($filters['producttype']) || $filters['crueltyfree'] !== '';

if ($hasFilters) {
    $products = Product::getFiltered($filters, $perPage, $offset);
    $totalProducts = count($products);
} else {
    $totalProducts = Product::countAll();
    $products = Product::getPaginated($perPage, $offset);
}

$totalPages = ceil($totalProducts / $perPage);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>GlowCare Webshop</title>
  <link rel="stylesheet" href="style2.css" />
</head>
<body>

<header class="hero">
  <h1>Ontdek Skincare Producten</h1>
  <p>Verwen je huid met onze zorgvuldig geselecteerde huidverzorgingsproducten.</p>
</header>

<section class="product-overview">
  <h2>Onze Producten</h2>

  <div class="product-layout">
    <!-- FILTERS -->
    <aside class="filters">
      <form id="filter-form" method="GET" action="home.php">

        <h3>Producttype</h3>
        <label>
          <input type="checkbox" name="producttype[]" value="Skin Care"
            <?= in_array('Skin Care', $_GET['producttype'] ?? []) ? 'checked' : '' ?>>
          Skin Care
        </label>
        <label>
          <input type="checkbox" name="producttype[]" value="Body Care"
            <?= in_array('Body Care', $_GET['producttype'] ?? []) ? 'checked' : '' ?>>
          Body Care
        </label>

        <h3>Merk</h3>
        <label><input type="checkbox" name="brand[]" value="I'm From"
          <?= in_array("I'm From", $_GET['brand'] ?? []) ? 'checked' : '' ?>> I'm From</label>
        <label><input type="checkbox" name="brand[]" value="By Wishtrend"
          <?= in_array('By Wishtrend', $_GET['brand'] ?? []) ? 'checked' : '' ?>> By Wishtrend</label>
        <label><input type="checkbox" name="brand[]" value="Dear, Klairs"
          <?= in_array('Dear, Klairs', $_GET['brand'] ?? []) ? 'checked' : '' ?>> Dear, Klairs</label>
        <label><input type="checkbox" name="brand[]" value="COSRX"
          <?= in_array('COSRX', $_GET['brand'] ?? []) ? 'checked' : '' ?>> COSRX</label>

        <h3>Categorie</h3>
        <label><input type="checkbox" name="category[]" value="Reiniger"
          <?= in_array('Cleanser', $_GET['category'] ?? []) ? 'checked' : '' ?>> Reiniger</label>
        <label><input type="checkbox" name="category[]" value="Toner"
          <?= in_array('Toner', $_GET['category'] ?? []) ? 'checked' : '' ?>> Toner</label>
        <label><input type="checkbox" name="category[]" value="Serum & Ampoule"
          <?= in_array('Serum & Ampoule', $_GET['category'] ?? []) ? 'checked' : '' ?>> Serum & Ampoule</label>
        <label><input type="checkbox" name="category[]" value="Masker"
          <?= in_array('Mask', $_GET['category'] ?? []) ? 'checked' : '' ?>> Masker</label>
        <label><input type="checkbox" name="category[]" value="Hydraterende Crème"
          <?= in_array('Moisturizer', $_GET['category'] ?? []) ? 'checked' : '' ?>> Hydraterende Crème</label>

        <h3>Cruelty Free</h3>
        <label><input type="radio" name="crueltyfree" value="1"
          <?= (isset($_GET['crueltyfree']) && $_GET['crueltyfree'] == '1') ? 'checked' : '' ?>> Ja</label>
        <label><input type="radio" name="crueltyfree" value="0"
          <?= (isset($_GET['crueltyfree']) && $_GET['crueltyfree'] == '0') ? 'checked' : '' ?>> Nee</label>

        <button type="submit" class="filter-btn">Toon Resultaten</button>
      </form>
    </aside>

    <!-- PRODUCTEN -->
    <div class="products">
      <?php if (empty($products)): ?>
        <p>Geen producten gevonden.</p>
      <?php else: ?>
        <?php foreach ($products as $product): ?>
          <div class="product-card">
            <a href="detailpagina.php?id=<?= htmlspecialchars($product['Product_ID']); ?>" class="product-card-link">
              <img src="images/<?= htmlspecialchars($product['Image']); ?>" alt="<?= htmlspecialchars($product['Product_Name']); ?>" />
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

        <!-- PAGINATIE -->
        <div class="pagination">
          <?php
          $queryParams = $_GET;
          for ($i = 1; $i <= $totalPages; $i++):
            $queryParams['page'] = $i;
            $queryStr = http_build_query($queryParams);
          ?>
            <a href="?<?= $queryStr; ?>" class="<?= $i === $page ? 'active' : ''; ?>"><?= $i; ?></a>
          <?php endfor; ?>
        </div>

      <?php endif; ?>
    </div>
  </div>
</section>

<footer>
  <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
</footer>

</body>
</html>
