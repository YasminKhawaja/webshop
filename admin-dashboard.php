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
      <a href="admin-dashboard.html" class="logo">GlowCare Admin</a>
      <div class="nav-actions">
        <a href="admin-add-product.html" class="add-product-btn"
          >+ Nieuw product</a
        >
        <a href="logout.php" class="logout-btn">Uitloggen</a>
      </div>
    </nav>

    <main class="dashboard-container">
      <h1>Productbeheer</h1>

      <!-- Filters -->
      <div class="filter-bar">
        <select>
          <option value="">Filter op categorie</option>
          <option>Reiniger</option>
          <option>Toner</option>
          <option>Serum & Ampule</option>
          <option>Masker</option>
          <option>Hydraterende Crème</option>
        </select>

        <select>
          <option value="">Filter op merk</option>
          <option>I'm From</option>
          <option>By Wishtrend</option>
          <option>Dear, Klairs</option>
          <option>COSRX</option>
        </select>

        <select>
          <option value="">Producttype</option>
          <option>Skin Care</option>
          <option>Body Care</option>
        </select>

        <select>
          <option value="">Cruelty Free</option>
          <option>Ja</option>
          <option>Nee</option>
        </select>

        <input type="text" placeholder="Zoek op naam of ID..." />
        <button class="filter-btn">Zoeken</button>
      </div>

      <!-- Producttabel -->
      <table class="product-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Merk</th>
            <th>Categorie</th>
            <th>Producttype</th>
            <th>Cruelty Free</th>
            <th>Afbeelding</th>
            <th>Acties</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Gentle Cleanser</td>
            <td>Dear, Klairs</td>
            <td>Reiniger</td>
            <td>Skin Care</td>
            <td>Ja</td>
            <td><img src="images/cleanser.jpg" alt="Cleanser" /></td>
            <td>
              <a href="admin-edit-product.html" class="edit-btn">Bewerken</a>
              <a href="#" class="delete-btn">Verwijderen</a>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Hydrating Serum</td>
            <td>COSRX</td>
            <td>Serum & Ampule</td>
            <td>Skin Care</td>
            <td>Nee</td>
            <td><img src="images/serum.jpg" alt="Serum" /></td>
            <td>
              <a href="admin-edit-product.html" class="edit-btn">Bewerken</a>
              <a href="#" class="delete-btn">Verwijderen</a>
            </td>
          </tr>
        </tbody>
      </table>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Admin Panel - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
