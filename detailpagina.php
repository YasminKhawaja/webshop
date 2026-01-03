<?php
session_start();
require_once(__DIR__ . "/classes/Database.php");
require_once(__DIR__ . "/classes/Cart.php");
include_once("nav.inc.php");

// --- Huidig product ophalen ---
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$conn = Database::getConnection();

$stmt = $conn->prepare("
    SELECT p.*, b.Brand, c.Category, t.Type
    FROM webshop.products p
    LEFT JOIN webshop.brands b ON p.Brand_ID = b.Brand_ID
    LEFT JOIN webshop.categories c ON p.Category_ID = c.Category_ID
    LEFT JOIN webshop.types t ON p.Type_ID = t.Type_ID
    WHERE p.Product_ID = :id
");
$stmt->bindValue(":id", $productId, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p style='color:red; text-align:center;'>Product niet gevonden.</p>";
    exit;
}

// --- Controleren of user mag reviewen ---
$canReview = false;
if (isset($_SESSION['user_id'])) {
    $checkPurchase = $conn->prepare("
        SELECT COUNT(*) 
        FROM webshop.product_ordered po
        JOIN webshop.orders o ON po.Order_ID = o.Order_ID
        WHERE o.User_ID = :userId AND po.Product_ID = :productId
    ");
    $checkPurchase->bindValue(":userId", $_SESSION['user_id'], PDO::PARAM_INT);
    $checkPurchase->bindValue(":productId", $productId, PDO::PARAM_INT);
    $checkPurchase->execute();
    $canReview = $checkPurchase->fetchColumn() > 0;
}
?>
<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($product['Product_Name']); ?> - GlowCare</title>
    <link rel="stylesheet" href="style2.css" />
  </head>
  <body>

    <!-- Dit blok is ALTIJD aanwezig en geeft product_id door aan JS -->
    <div id="page-data" data-product-id="<?= $productId; ?>"></div>

    <main class="product-detail">
      <div class="image-gallery">
        <img
          src="images/<?= htmlspecialchars($product['Image']); ?>"
          alt="<?= htmlspecialchars($product['Product_Name']); ?>"
          class="main-image"
        />
      </div>

      <div class="product-info">
        <h1><?= htmlspecialchars($product['Product_Name']); ?></h1>
        <p class="brand"><strong>Merk:</strong> <?= htmlspecialchars($product['Brand']); ?></p>
        <p class="description"><?= htmlspecialchars($product['Description']); ?></p>
        <p class="cruelty-free">
          <strong>Cruelty Free:</strong> <?= $product['Is_Cruelty_Free'] ? 'Ja' : 'Nee'; ?>
        </p>
        <p class="price"><strong>Prijs:</strong> €<?= number_format($product['Price'], 2, ',', '.'); ?></p>

        <div class="purchase-section">
          <form action="winkelwagen.php" method="POST">
            <input type="hidden" name="id" value="<?= $product['Product_ID']; ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($product['Product_Name']); ?>">
            <input type="hidden" name="price" value="<?= $product['Price']; ?>">
            <label for="quantity">Aantal:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" />
            <button type="submit" class="add-to-cart">In winkelwagen</button>
          </form>
        </div>
      </div>
    </main>

    <!-- Reviews sectie -->
    <div class="reviews-section">
      <h2>Reviews</h2>
      <div id="reviews-container">
        <p>Reviews worden geladen...</p>
      </div>
    </div>

    <!-- Schrijf review sectie -->
    <div class="write-comment">
      <h3>Schrijf een review</h3>

      <?php if (!isset($_SESSION['user_id'])): ?>
        <p style="color:red;">Log in om een review te schrijven.</p>
      <?php elseif (!$canReview): ?>
        <p style="color:gray;">Je kunt alleen een review plaatsen als je dit product hebt gekocht.</p>
      <?php else: ?>
        <form id="comment-form">
          <!-- hidden product_id alleen voor het POSTen, JS gebruikt page-data -->
          <input type="hidden" name="product_id" value="<?= $productId; ?>">
          <textarea
            name="comment"
            id="comment-text"
            rows="4"
            placeholder="Schrijf hier je review..."
            required
          ></textarea>
          <button type="submit">Verzenden</button>
        </form>
      <?php endif; ?>
    </div>

    <footer>
      <p>&copy; 2025 GlowCare Webshop - Alle rechten voorbehouden.</p>
    </footer>

    <!-- AJAX script -->
    <script>
    document.addEventListener("DOMContentLoaded", () => {
      const pageData = document.getElementById("page-data");
      const productId = pageData ? pageData.dataset.productId : null;
      const reviewsContainer = document.getElementById("reviews-container");

      if (!productId) {
        reviewsContainer.innerHTML = "<p>Product niet gevonden.</p>";
        return;
      }

      // --- Reviews ophalen ---
      fetch(`ajax-get-comments.php?product_id=${productId}`)
        .then(res => res.json())
        .then(data => {
          if (!Array.isArray(data) || data.length === 0) {
            reviewsContainer.innerHTML = "<p>Nog geen reviews.</p>";
            return;
          }
          reviewsContainer.innerHTML = data.map(r => `
            <div class="review">
              <p class="reviewer-name">${r.First_Name} ${r.Last_Name}</p>
              <p class="review-date">${new Date(r.Created_At).toLocaleDateString('nl-BE')}</p>
              <p class="review-text">${r.Comment}</p>
            </div>
          `).join("");
        })
        .catch(() => {
          reviewsContainer.innerHTML = "<p>Er ging iets mis bij het laden van reviews.</p>";
        });

      // --- Nieuwe review toevoegen ---
      const form = document.getElementById("comment-form");
      if (form) {
        form.addEventListener("submit", (e) => {
          e.preventDefault();
          const commentField = document.getElementById("comment-text");
          const comment = commentField.value.trim();
          if (!comment) return;

          // Pak product_id uit het form of uit page-data
          const formProductIdInput = form.querySelector('[name="product_id"]');
          const formProductId = formProductIdInput ? formProductIdInput.value : productId;

          fetch("ajax-add-comment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
              product_id: formProductId,
              comment: comment
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.error) {
              alert(data.error);
            } else {
              commentField.value = "";
              // Reviews opnieuw laden
              return fetch(`ajax-get-comments.php?product_id=${productId}`)
                .then(res => res.json())
                .then(data => {
                  if (!Array.isArray(data) || data.length === 0) {
                    reviewsContainer.innerHTML = "<p>Nog geen reviews.</p>";
                    return;
                  }
                  reviewsContainer.innerHTML = data.map(r => `
                    <div class="review">
                      <p class="reviewer-name">${r.First_Name} ${r.Last_Name}</p>
                      <p class="review-date">${new Date(r.Created_At).toLocaleDateString('nl-BE')}</p>
                      <p class="review-text">${r.Comment}</p>
                    </div>
                  `).join("");
                });
            }
          })
          .catch(() => {
            alert("Er ging iets mis bij het versturen van je review.");
          });
        });
      }
    });
    </script>
  </body>
</html>
