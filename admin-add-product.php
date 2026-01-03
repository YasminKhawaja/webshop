<?php
session_start();
require_once(__DIR__ . "/classes/Database.php");
require_once(__DIR__ . "/classes/Product.php");

// Alleen admins mogen binnen
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";

// === Formulierverwerking ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $productName = trim($_POST['product_name']);
        $brand = trim($_POST['brand']);
        $category = trim($_POST['category']);
        $type = trim($_POST['product_type']);
        $price = floatval($_POST['price']);
        $description = trim($_POST['description']);
        $crueltyFree = ($_POST['cruelty_free'] === "Ja") ? 1 : 0;

        // === Validatie ===
        if (empty($productName) || empty($brand) || empty($category) || empty($type) || $price <= 0) {
            throw new Exception("Vul alle verplichte velden correct in.");
        }

        // === Afbeelding upload ===
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
        } else {
            throw new Exception("Afbeelding is verplicht.");
        }

        // === Database insert ===
        $conn = Database::getConnection();

        // IDs ophalen van gerelateerde tabellen
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

        // Product opslaan
        $stmt = $conn->prepare("
            INSERT INTO products (Product_Name, Description, Image, Price, Category_ID, Type_ID, Brand_ID, Is_Cruelty_Free)
            VALUES (:name, :description, :image, :price, :categoryId, :typeId, :brandId, :crueltyFree)
        ");
        $stmt->bindValue(":name", $productName);
        $stmt->bindValue(":description", $description);
        $stmt->bindValue(":image", $fileName);
        $stmt->bindValue(":price", $price);
        $stmt->bindValue(":categoryId", $categoryId);
        $stmt->bindValue(":typeId", $typeId);
        $stmt->bindValue(":brandId", $brandId);
        $stmt->bindValue(":crueltyFree", $crueltyFree);
        $stmt->execute();

        $success = "✅ Product succesvol toegevoegd!";
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
    <title>Nieuw product toevoegen - GlowCare Admin</title>
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

    <main class="add-product-container">
      <h1>Nieuw Product Toevoegen</h1>

      <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success); ?></div>
      <?php elseif (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form
        class="add-product-form"
        action=""
        method="POST"
        enctype="multipart/form-data"
      >
        <label for="product-name">Productnaam *</label>
        <input type="text" id="product-name" name="product_name" placeholder="Bijv. Gentle Cleanser" required />

        <label for="brand">Merk *</label>
        <select id="brand" name="brand" required>
          <option value="">Selecteer merk</option>
          <option>I'm From</option>
          <option>By Wishtrend</option>
          <option>Dear, Klairs</option>
          <option>COSRX</option>
        </select>

        <label for="category">Categorie *</label>
        <select id="category" name="category" required>
          <option value="">Selecteer categorie</option>
          <option>Reiniger</option>
          <option>Toner</option>
          <option>Serum & Ampule</option>
          <option>Masker</option>
          <option>Hydraterende Crème</option>
        </select>

        <label for="product-type">Producttype *</label>
        <select id="product-type" name="product_type" required>
          <option value="">Selecteer type</option>
          <option>Skin Care</option>
          <option>Body Care</option>
        </select>

        <label for="cruelty-free">Cruelty Free *</label>
        <select id="cruelty-free" name="cruelty_free" required>
          <option value="">Maak een keuze</option>
          <option>Ja</option>
          <option>Nee</option>
        </select>

        <label for="price">Prijs (€) *</label>
        <input type="number" id="price" name="price" step="0.01" min="0" placeholder="Bijv. 24.95" required />

        <label for="description">Beschrijving</label>
        <textarea id="description" name="description" rows="4" placeholder="Korte beschrijving van het product..."></textarea>

        <label for="image">Afbeelding *</label>
        <input type="file" id="image" name="image" accept="image/*" required />

        <button type="submit" class="save-btn">Product Opslaan</button>
      </form>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Admin Panel - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
