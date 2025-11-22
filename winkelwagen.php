<?php 
include_once("nav.inc.php");

?><!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Winkelwagen - GlowCare</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>

    <main class="cart-container">
      <h2>🛍️ Mijn Winkelwagen</h2>

      <div class="cart-items">
        <!-- Product 1 -->
        <div class="cart-item">
          <img src="images/cleanser.jpg" alt="Gentle Cleanser" />
          <div class="item-info">
            <h3>Gentle Cleanser</h3>
            <p>Prijs: €19,95</p>
            <label>
              Aantal:
              <input type="number" value="1" min="1" />
            </label>
          </div>
          <div class="item-subtotal">
            <p>Subtotaal: €19,95</p>
            <button class="remove-btn">Verwijderen</button>
          </div>
        </div>

        <!-- Product 2 -->
        <div class="cart-item">
          <img src="images/serum.jpg" alt="Hydrating Serum" />
          <div class="item-info">
            <h3>Hydrating Serum</h3>
            <p>Prijs: €24,95</p>
            <label>
              Aantal:
              <input type="number" value="2" min="1" />
            </label>
          </div>
          <div class="item-subtotal">
            <p>Subtotaal: €49,90</p>
            <button class="remove-btn">Verwijderen</button>
          </div>
        </div>
      </div>

      <div class="cart-summary">
        <h3>Totaaloverzicht</h3>
        <p><strong>Totaal:</strong> €69,85</p>
        <button class="checkout-btn">Afrekenen</button>
      </div>
    </main>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>
  </body>
</html>
