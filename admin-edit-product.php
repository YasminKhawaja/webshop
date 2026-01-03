<?php
session_start();
require_once(__DIR__ . "/classes/Database.php");

// --- Alleen admins mogen binnen ---
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$conn = Database::getConnection();
$success = "";
$error = "";

// === Ophalen huidig product ===
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin-dashboard.php");
    exit;
}

$productId = (int)$_GET['id'];

$stmt = $conn->prepare("
    SELECT p.*, b.Brand, c.Category, t.Type
    FROM products p
    LEFT JOIN brands b ON p.Brand_ID = b.Brand_ID
    LEFT JOIN categories c ON p.Category_ID = c.Category_ID
    LEFT JOIN types t ON p.Type_ID = t.Type_ID
    WHERE p.Product_ID = :id
");
$stmt->bindValue(":id", $productId, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $error = "Product niet gevonden.";
}

// === Bewerking opslaan ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $productName = trim($_POST['product_name']);
        $brand = trim($_POST['brand']);
        $category = trim($_POST['category']);
        $type = trim($_POST['product_type']);
        $price = floatval($_POST['price']);
        $description = trim($_POST['description']);
        $crueltyFree = ($_POST['cruelty_free'] === "Ja") ? 1 : 0;

        if (empty($productName) || empty($brand) || empty($category) || empty($type) || $price <= 0) {
            throw new Exception("Vul alle verplichte velden correct in.");
        }

        // IDs ophalen
        $stmtBrand = $conn->prepare("SELECT Brand_ID FROM brands WHERE Brand = :brand");
        $stmtBrand->bindValue(":brand", $brand);
        $stmtBrand->execute();
        $brandId = $stmtBrand->fetchColumn();

        $stmtCat = $conn->prepare("SELECT Category_ID FROM categories WHERE Category = :category");
        $stmtCat->bindValue(":category", $category);
        $stmtCat->execute();
        $categoryId = $stmtCat->fetchColumn();

        $stmtType = $conn->prepare("SELECT Type_ID FROM types WHERE Type = :type");
        $stmtType->bindValue(":type", $type);
        $stmtType->execute();
        $typeId = $stmtType->fetchColumn();

        if (!$brandId || !$categoryId || !$typeId) {
            throw new Exception("Merk, categorie of type niet gevonden in database.");
        }

        // Afbeelding uploaden 
        $newImage = $product['Image'];
        if (!empty($_FILES['image']['name'])) {
            $targetDir = __DIR__ . "/images/";
            $fileName = basename($_FILES["image"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Alleen JPG, JPEG, PNG of WEBP bestanden zijn toegestaan.");
            }

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                throw new Exception("Uploaden van afbeelding mislukt.");
            }

            $newImage = $fileName;
        }

        // Database bijwerken
        $update = $conn->prepare("
            UPDATE products
            SET Product_Name = :name,
                Description = :description,
                Image = :image,
                Price = :price,
                Category_ID = :categoryId,
                Type_ID = :typeId,
                Brand_ID = :brandId,
                Is_Cruelty_Free = :crueltyFree
            WHERE Product_ID = :id
        ");
        $update->bindValue(":name", $productName);
        $update->bindValue(":description", $description);
        $update->bindValue(":image", $newImage);
        $update->bindValue(":price", $price);
        $update->bindValue(":categoryId", $categoryId);
        $update->bindValue(":typeId", $typeId);
        $update->bindValue(":brandId", $brandId);
        $update->bindValue(":crueltyFree", $crueltyFree, PDO::PARAM_BOOL);
        $update->bindValue(":id", $productId, PDO::PARAM_INT);
        $update->execute();

        $success = "✅ Product succesvol bijgewerkt!";
        // Product opnieuw ophalen voor de nieuwe waarden
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = "❌ " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product bewerken - GlowCare Admin</title>
    <link rel="stylesheet" href="admin.css" />
  </head>
  <body>
    <nav class="navbar">
      <a href="admin-dashboard.php" class="logo">GlowCare Admin</a>
      <div class="nav-actions">
        <a href="admin-dashboard.php" class="back-btn">← Terug naar Dashboard</a>
        <a href="logout.php" class="logout-btn">Uitloggen</a>
      </div>
    </nav>

    <main class="edit-product-container">
      <h1>Product Bewerken</h1>

      <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success); ?></div>
      <?php elseif (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <?php if ($product): ?>
      <form
        class="edit-product-form"
        action="?id=<?= htmlspecialchars($productId); ?>"
        method="POST"
        enctype="multipart/form-data"
      >
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($productId); ?>" />

        <label for="product-name">Productnaam *</label>
        <input type="text" id="product-name" name="product_name" value="<?= htmlspecialchars($product['Product_Name']); ?>" required />

        <label for="brand">Merk *</label>
        <select id="brand" name="brand" required>
          <?php
          $brands = ['I\'m From', 'By Wishtrend', 'Dear, Klairs', 'COSRX'];
          foreach ($brands as $b) {
              $selected = ($product['Brand'] === $b) ? 'selected' : '';
              echo "<option $selected>$b</option>";
          }
          ?>
        </select>

        <label for="category">Categorie *</label>
        <select id="category" name="category" required>
          <?php
          $categories = ['Reiniger', 'Toner', 'Serum & Ampoule', 'Masker', 'Hydraterende Crème'];
          foreach ($categories as $c) {
              $selected = ($product['Category'] === $c) ? 'selected' : '';
              echo "<option $selected>$c</option>";
          }
          ?>
        </select>

        <label for="product-type">Producttype *</label>
        <select id="product-type" name="product_type" required>
          <?php
          $types = ['Skin Care', 'Body Care'];
          foreach ($types as $t) {
              $selected = ($product['Type'] === $t) ? 'selected' : '';
              echo "<option $selected>$t</option>";
          }
          ?>
        </select>

        <label for="cruelty-free">Cruelty Free *</label>
        <select id="cruelty-free" name="cruelty_free" required>
          <option <?= $product['Is_Cruelty_Free'] ? 'selected' : ''; ?>>Ja</option>
          <option <?= !$product['Is_Cruelty_Free'] ? 'selected' : ''; ?>>Nee</option>
        </select>

        <label for="price">Prijs (€) *</label>
        <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($product['Price']); ?>" required />

        <label for="description">Beschrijving</label>
        <textarea id="description" name="description" rows="4"><?= htmlspecialchars($product['Description']); ?></textarea>

        <div class="image-preview">
          <p>Huidige afbeelding:</p>
          <img src="images/<?= htmlspecialchars($product['Image']); ?>" alt="Huidige productafbeelding" width="120" />
        </div>

        <label for="image">Nieuwe afbeelding (optioneel)</label>
        <input type="file" id="image" name="image" accept="image/*" />

        <div class="button-row">
          <button type="submit" class="update-btn">Wijzigingen Opslaan</button>
          <a href="admin-dashboard.php" class="cancel-btn">Annuleren</a>
        </div>
      </form>
      <?php endif; ?>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Admin Panel - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
