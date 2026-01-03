<?php
session_start();
require_once(__DIR__ . "/classes/Product.php");

// --- Toegangscontrole ---
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin-login.php");
    exit;
}

// --- Filters ophalen ---
$category = $_GET['category'] ?? '';
$brand = $_GET['brand'] ?? '';
$search = $_GET['search'] ?? '';

// --- Producten ophalen met filters ---
$products = Product::getFilteredAdmin($category, $brand, $search);
?>

<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GlowCare Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css" />
  </head>
  <body>
    <nav class="navbar">
      <a href="admin-dashboard.php" class="logo">GlowCare Admin</a>
      <div class="nav-actions">
        <a href="admin-add-product.php" class="add-product-btn">+ Nieuw product</a>
        <a href="logout.php" class="logout-btn">Uitloggen</a>
      </div>
    </nav>

    <main class="dashboard-container">
      <h1>Welkom terug, <?= htmlspecialchars($_SESSION['first_name']); ?> 👋</h1>

      <?php if (isset($_GET['success']) && $_GET['success'] === 'verwijderd'): ?>
        <div class="success-message">✅ Product succesvol verwijderd!</div>
      <?php elseif (isset($_GET['error'])): ?>
        <div class="error-message">❌ <?= htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <h2>Productbeheer</h2>

      <form method="GET" class="filter-bar" action="admin-dashboard.php">
  <select name="category">
    <option value="">Filter op categorie</option>
    <option value="Reiniger" <?= $category === 'Reiniger' ? 'selected' : '' ?>>Reiniger</option>
    <option value="Toner" <?= $category === 'Toner' ? 'selected' : '' ?>>Toner</option>
    <option value="Serum & Ampoule" <?= $category === 'Serum & Ampoule' ? 'selected' : '' ?>>Serum & Ampoule</option>
    <option value="Masker" <?= $category === 'Masker' ? 'selected' : '' ?>>Masker</option>
    <option value="Hydraterende Crème" <?= $category === 'Hydraterende Crème' ? 'selected' : '' ?>>Hydraterende Crème</option>
  </select>

  <select name="brand">
    <option value="">Filter op merk</option>
    <option value="I'm From" <?= $brand === "I'm From" ? 'selected' : '' ?>>I'm From</option>
    <option value="By Wishtrend" <?= $brand === 'By Wishtrend' ? 'selected' : '' ?>>By Wishtrend</option>
    <option value="Dear, Klairs" <?= $brand === 'Dear, Klairs' ? 'selected' : '' ?>>Dear, Klairs</option>
    <option value="COSRX" <?= $brand === 'COSRX' ? 'selected' : '' ?>>COSRX</option>
  </select>

  <input type="text" name="search" placeholder="Zoek op naam of ID..." value="<?= htmlspecialchars($search); ?>" />
  <button type="submit" class="filter-btn">Zoeken</button>
  <!-- <a href="admin-dashboard.php" class="filter-btn">Reset</a> -->
</form>


      <table class="product-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Merk</th>
            <th>Categorie</th>
            <th>Producttype</th>
            <th>Prijs</th>
            <th>Cruelty Free</th>
            <th>Afbeelding</th>
            <th>Acties</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($products)): ?>
            <tr>
              <td colspan="9" style="text-align:center;">Geen producten gevonden.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($products as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p['Product_ID']); ?></td>
                <td><?= htmlspecialchars($p['Product_Name']); ?></td>
                <td><?= htmlspecialchars($p['Brand'] ?? '-'); ?></td>
                <td><?= htmlspecialchars($p['Category'] ?? '-'); ?></td>
                <td><?= htmlspecialchars($p['Type'] ?? '-'); ?></td>
                <td>€<?= number_format($p['Price'], 2, ',', '.'); ?></td>
                <td><?= $p['Is_Cruelty_Free'] ? 'Ja' : 'Nee'; ?></td>
                <td>
                  <?php if (!empty($p['Image'])): ?>
                    <img src="images/<?= htmlspecialchars($p['Image']); ?>" 
                         alt="<?= htmlspecialchars($p['Product_Name']); ?>" width="60">
                  <?php else: ?>
                    Geen afbeelding
                  <?php endif; ?>
                </td>
                <td>
                  <a href="admin-edit-product.php?id=<?= $p['Product_ID']; ?>" class="edit-btn">Bewerken</a>
                  <a href="admin-delete-product.php?id=<?= $p['Product_ID']; ?>"
                     class="delete-btn"
                     onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">
                     Verwijderen
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Admin Panel - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
