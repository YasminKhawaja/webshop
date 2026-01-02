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

// --- Reviews ophalen ---
$reviewsStmt = $conn->prepare("
    SELECT c.Comment, c.Created_At, u.First_Name
    FROM webshop.Comments c
    JOIN webshop.users u ON c.User_ID = u.User_ID
    WHERE c.Product_ID = :productId AND c.Is_Active = 1
    ORDER BY c.Created_At DESC
");
$reviewsStmt->bindValue(":productId", $productId, PDO::PARAM_INT);
$reviewsStmt->execute();
$reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);

// --- Controleren of user mag reviewen ---
$canReview = false;
if (isset($_SESSION['user_id'])) {
    $checkPurchase = $conn->prepare("
        SELECT COUNT(*) 
        FROM webshop.product_ordered po
        JOIN webshop.orders o ON po.Order_ID = o.Order_ID
        WHERE po.User_ID = :userId AND po.Product_ID = :productId
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

    <!-- ✅ Feedbackmelding na het posten -->
    <?php if (isset($_GET['review']) && $_GET['review'] === 'success'): ?>
      <p style="color:green; text-align:center; font-weight:bold;">✅ Bedankt voor je review!</p>
    <?php elseif (isset($_GET['review']) && $_GET['review'] === 'forbidden'): ?>
      <p style="color:red; text-align:center;">❌ Je kunt enkel een review plaatsen voor producten die je hebt gekocht.</p>
    <?php endif; ?>

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

      <?php if (empty($reviews)): ?>
        <p>Er zijn nog geen reviews voor dit product.</p>
      <?php else: ?>
        <?php foreach ($reviews as $review): ?>
          <div class="review">
            <p class="reviewer-name"><?= htmlspecialchars($review['First_Name']); ?></p>
            <p class="review-date"><?= date('d M Y', strtotime($review['Created_At'])); ?></p>
            <p class="review-text"><?= htmlspecialchars($review['Comment']); ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Schrijf review sectie -->
    <div class="write-comment">
      <h3>Schrijf een review</h3>

      <?php if (!isset($_SESSION['user_id'])): ?>
        <p style="color:red;">Log in om een review te schrijven.</p>
      <?php elseif (!$canReview): ?>
        <p style="color:gray;">Je kunt alleen een review plaatsen als je dit product hebt gekocht.</p>
      <?php else: ?>
        <form action="post_comment.php" method="POST" id="comment-form">
          <input type="hidden" name="product_id" value="<?= $productId; ?>">
          <textarea
            name="comment-text"
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
  </body>
</html>
