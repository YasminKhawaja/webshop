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
      <a href="admin-dashboard.html" class="logo">GlowCare Admin</a>
      <div class="nav-actions">
        <a href="admin-dashboard.html" class="back-btn"
          >← Terug naar Dashboard</a
        >
        <a href="logout.php" class="logout-btn">Uitloggen</a>
      </div>
    </nav>

    <main class="add-product-container">
      <h1>Nieuw Product Toevoegen</h1>

      <form
        class="add-product-form"
        action="#"
        method="POST"
        enctype="multipart/form-data"
      >
        <label for="product-name">Productnaam *</label>
        <input
          type="text"
          id="product-name"
          name="product_name"
          placeholder="Bijv. Gentle Cleanser"
          required
        />

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
        <input
          type="number"
          id="price"
          name="price"
          step="0.01"
          min="0"
          placeholder="Bijv. 24.95"
          required
        />

        <label for="description">Beschrijving</label>
        <textarea
          id="description"
          name="description"
          rows="4"
          placeholder="Korte beschrijving van het product..."
        ></textarea>

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
