<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>GlowCare Webshop</title>
    <link rel="stylesheet" href="style2.css" />
    <style></style>
  </head>
  <body>
    <nav class="navbar">
      <a href="home.html" class="logo">GlowCare</a>

      <form class="search-form" action="/search" method="GET">
        <input
          type="search"
          name="q"
          placeholder="Waar ben je naar op zoek?"
          aria-label="Zoeken"
          required
        />
        <button type="submit" aria-label="Zoeken">Zoek</button>
      </form>

      <div class="nav-actions">
        <a href="login.html" class="login-btn">Inloggen</a>
        <a href="winkelwagen.html" class="cart-btn" aria-label="Winkelwagen"
          >🛒</a
        >
        <a href="account.html">Account</a>
      </div>
    </nav>

    <header class="hero">
      <h1>Ontdek Skincare Producten</h1>
      <p>
        Verwen je huid met onze zorgvuldig geselecteerde
        huidverzorgingsproducten.
      </p>
    </header>

    <!-- PRODUCT OVERVIEW MET FILTERS LINKS -->
    <section class="product-overview">
      <h2>Onze Producten</h2>

      <div class="product-layout">
        <!-- Filters links -->
        <aside class="filters">
          <form id="filter-form">
            <h3>Producttype</h3>
            <label
              ><input type="checkbox" name="producttype" value="Skin Care" />
              Skin Care</label
            >
            <label
              ><input type="checkbox" name="producttype" value="Body Care" />
              Body Care</label
            >

            <h3>Merk</h3>
            <label
              ><input type="checkbox" name="brand" value="I'm From" /> I'm
              From</label
            >
            <label
              ><input type="checkbox" name="brand" value="By Wishtrend" /> By
              Wishtrend</label
            >
            <label
              ><input type="checkbox" name="brand" value="Dear, Klairs" /> Dear,
              Klairs</label
            >
            <label
              ><input type="checkbox" name="brand" value="COSRX" /> COSRX</label
            >

            <h3>Categorie</h3>
            <label
              ><input type="checkbox" name="category" value="Cleanser" />
              Reiniger</label
            >
            <label
              ><input type="checkbox" name="category" value="Toner" />
              Toner</label
            >
            <label
              ><input type="checkbox" name="category" value="Serum & Ampoule" />
              Serum & Ampoule</label
            >
            <label
              ><input type="checkbox" name="category" value="Mask" />
              Masker</label
            >
            <label
              ><input
                type="checkbox"
                name="category"
                value="Moisturizer"
              />Hydraterende Crème</label
            >

            <h3>Cruelty Free</h3>
            <label
              ><input type="radio" name="crueltyfree" value="ja" /> Ja</label
            >
            <label
              ><input type="radio" name="crueltyfree" value="nee" /> Nee</label
            >

            <button type="submit" class="filter-btn">Toon Resultaten</button>
          </form>
        </aside>

        <!-- Producten rechts -->
        <div class="products">
          <a href="detailpagina.html" class="product-card-link">
            <div class="product-card">
              <img src="images/cleanser.jpg" alt="Gentle Cleanser" />
              <h3>Gentle Cleanser</h3>
              <p>Voor een frisse, zachte huid.</p>
              <span class="price">€19,95</span>
              <button>Toevoegen aan winkelmand</button>
            </div>
          </a>

          <a href="detailpagina.html" class="product-card-link">
            <div class="product-card">
              <img src="images/serum.jpg" alt="Hydrating Serum" />
              <h3>Hydrating Serum</h3>
              <p>Intense hydratatie voor alle huidtypes.</p>
              <span class="price">€24,95</span>
              <button>Toevoegen aan winkelmand</button>
            </div>
          </a>

          <a href="detailpagina.html" class="product-card-link">
            <div class="product-card">
              <img src="images/serum.jpg" alt="Hydrating Serum" />
              <h3>Hydrating Serum</h3>
              <p>Intense hydratatie voor alle huidtypes.</p>
              <span class="price">€24,95</span>
              <button>Toevoegen aan winkelmand</button>
            </div>
          </a>

          <a href="detailpagina.html" class="product-card-link">
            <div class="product-card">
              <img src="images/serum.jpg" alt="Hydrating Serum" />
              <h3>Hydrating Serum</h3>
              <p>Intense hydratatie voor alle huidtypes.</p>
              <span class="price">€24,95</span>
              <button>Toevoegen aan winkelmand</button>
            </div>
          </a>

          <a href="detailpagina.html" class="product-card-link">
            <div class="product-card">
              <img src="images/serum.jpg" alt="Hydrating Serum" />
              <h3>Hydrating Serum</h3>
              <p>Intense hydratatie voor alle huidtypes.</p>
              <span class="price">€24,95</span>
              <button>Toevoegen aan winkelmand</button>
            </div>
          </a>

          <div class="product-card">
            <a href="detailpagina.html" class="product-card-link">
              <img src="images/serum.jpg" alt="Hydrating Serum" />
              <h3>Hydrating Serum</h3>
              <p>Intense hydratatie voor alle huidtypes.</p>
            </a>
            <span class="price">€24,95</span>
            <button class="add-to-cart">Toevoegen aan winkelmand</button>
          </div>
        </div>
      </div>
    </section>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
