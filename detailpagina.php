<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Product Detail - Gentle Cleanser</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>
    <nav class="navbar">
      <a href="home.php" class="logo">GlowCare</a>

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
        <a href="login.php" class="login-btn">Inloggen</a>
        <a href="winkelwagen.php" class="cart-btn" aria-label="Winkelwagen"
          >🛒</a
        >
      </div>
    </nav>

    <main class="product-detail">
      <div class="image-gallery">
        <img
          src="images/cleanser1.jpg"
          alt="Gentle Cleanser photo 1"
          class="main-image"
        />
        <div class="thumbs">
          <img src="images/cleanser1.jpg" alt="Gentle Cleanser thumb 1" />
          <img src="images/cleanser2.jpg" alt="Gentle Cleanser thumb 2" />
          <img src="images/cleanser3.jpg" alt="Gentle Cleanser thumb 3" />
        </div>
      </div>

      <div class="product-info">
        <h1>Gentle Cleanser</h1>
        <p class="brand">Brand: GlowCare</p>
        <p class="description">
          Deze milde cleanser reinigt je huid zacht en grondig zonder uit te
          drogen. Geschikt voor alle huidtypes.
        </p>
        <p class="cruelty-free"><strong>Cruelty Free:</strong> Nee</p>

        <div class="purchase-section">
          <label for="quantity">Aantal:</label>
          <input
            type="number"
            id="quantity"
            name="quantity"
            value="1"
            min="1"
          />
          <button class="add-to-cart">In winkelwagen</button>
        </div>
      </div>
    </main>

    <div class="reviews-section">
      <h2>Reviews</h2>
      <div class="review">
        <p class="reviewer-name">Sarah Em</p>
        <p class="review-date">12 okt 2025</p>
        <p class="review-text">
          Heerlijk product! Mijn huid voelt zacht en schoon.
        </p>
      </div>
      <div class="review">
        <p class="reviewer-name">Lorelien Michiels</p>
        <p class="review-date">8 okt 2025</p>
        <p class="review-text">
          Werkt goed, maar had iets sneller mogen drogen.
        </p>
      </div>
    </div>

    <div class="write-comment">
      <h3>Schrijf een review</h3>
      <form id="comment-form">
        <textarea
          name="comment-text"
          rows="4"
          placeholder="Schrijf hier je review..."
          required
        ></textarea>
        <button type="submit">Verzenden</button>
      </form>
    </div>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
