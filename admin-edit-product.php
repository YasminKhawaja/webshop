<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    // Enkel admins mogen hier komen
    header("Location: login.php");
    exit();
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
    <!-- === NAVBAR === -->
    <nav class="navbar">
      <a href="admin-dashboard.php" class="logo">GlowCare Admin</a>
      <div class="nav-actions">
        <a href="admin-dashboard.php" class="back-btn"
          >← Terug naar Dashboard</a
        >
        <a href="logout.php" class="logout-btn">Uitloggen</a>
      </div>
    </nav>

    <!-- === MAIN CONTENT === -->
    <main class="edit-product-container">
      <h1>Product Bewerken</h1>

      <form
        class="edit-product-form"
        action="#"
        method="POST"
        enctype="multipart/form-data"
      >
        <input type="hidden" name="product_id" value="1" />

        <label for="product-name">Productnaam *</label>
        <input
          type="text"
          id="product-name"
          name="product_name"
          value="Gentle Cleanser"
          required
        />

        <label for="brand">Merk *</label>
        <select id="brand" name="brand" required>
          <option>I'm From</option>
          <option>By Wishtrend</option>
          <option selected>Dear, Klairs</option>
          <option>COSRX</option>
        </select>

        <label for="category">Categorie *</label>
        <select id="category" name="category" required>
          <option>Reiniger</option>
          <option>Toner</option>
          <option selected>Serum & Ampule</option>
          <option>Masker</option>
          <option>Hydraterende Crème</option>
        </select>

        <label for="product-type">Producttype *</label>
        <select id="product-type" name="product_type" required>
          <option selected>Skin Care</option>
          <option>Body Care</option>
        </select>

        <label for="cruelty-free">Cruelty Free *</label>
        <select id="cruelty-free" name="cruelty_free" required>
          <option selected>Ja</option>
          <option>Nee</option>
        </select>

        <label for="price">Prijs (€) *</label>
        <input
          type="number"
          id="price"
          name="price"
          step="0.01"
          min="0"
          value="24.95"
          required
        />

        <label for="description">Beschrijving</label>
        <textarea id="description" name="description" rows="4">
Een zachte reiniger die vuil en make-up verwijdert zonder de huid uit te drogen.
        </textarea>

        <div class="image-preview">
          <p>Huidige afbeelding:</p>
          <img src="images/cleanser.jpg" alt="Huidige productafbeelding" />
        </div>

        <label for="image">Nieuwe afbeelding (optioneel)</label>
        <input type="file" id="image" name="image" accept="image/*" />

        <div class="button-row">
          <button type="submit" class="update-btn">Wijzigingen Opslaan</button>
          <a href="admin-dashboard.php" class="cancel-btn">Annuleren</a>
        </div>
      </form>
    </main>

    <!-- === FOOTER === -->
    <footer>
      <p>&copy; 2025 GlowCare Admin Panel - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
