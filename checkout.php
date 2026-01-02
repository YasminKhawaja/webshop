<?php
require_once(__DIR__ . "/classes/Cart.php");
require_once(__DIR__ . "/classes/User.php");

session_start();

// 1. Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

Cart::startSession();
$cart = Cart::getCart();
$total = Cart::getTotal();

// 2. Check of winkelmandje leeg is
if (empty($cart)) {
    echo "<p style='text-align:center; color:red;'>Je winkelwagen is leeg.</p>";
    echo "<p style='text-align:center;'><a href='home.php'>Terug naar shop</a></p>";
    exit;
}

// 3. Haal gebruiker op
$user = User::getById($_SESSION['user_id']);
$currentBalance = (float)$user['Account_Balance'];

// 4. Check saldo
if ($currentBalance < $total) {
    echo "<p style='text-align:center; color:red;'>Onvoldoende saldo!<br>Je hebt €" . number_format($currentBalance,2,',','.') . " maar de bestelling kost €" . number_format($total,2,',','.') . ".</p>";
    echo "<p style='text-align:center;'><a href='winkelwagen.php'>Terug naar winkelwagen</a></p>";
    exit;
}

try {
    // 5. Start transactie
    $conn = Database::getConnection();
    $conn->beginTransaction();

 // 6. Maak bestelling aan (nu mét User_ID)
$orderStmt = $conn->prepare("INSERT INTO orders (User_ID, Price) VALUES (:userId, :price)");
$orderStmt->bindValue(":userId", $_SESSION['user_id']);
$orderStmt->bindValue(":price", $total);
$orderStmt->execute();
$orderId = $conn->lastInsertId();


    // 7. Koppel producten aan bestelling
    $productStmt = $conn->prepare("
        INSERT INTO product_ordered (Product_ID, User_ID, Order_ID)
        VALUES (:productId, :userId, :orderId)
    ");

    foreach ($cart as $id => $item) {
        $productStmt->bindValue(":productId", $id);
        $productStmt->bindValue(":userId", $_SESSION['user_id']);
        $productStmt->bindValue(":orderId", $orderId);
        $productStmt->execute();
    }

    // 8. Werk saldo bij
    $newBalance = $currentBalance - $total;
    $updateBalance = $conn->prepare("UPDATE users SET Account_Balance = :balance WHERE User_ID = :id");
    $updateBalance->bindValue(":balance", $newBalance);
    $updateBalance->bindValue(":id", $_SESSION['user_id']);
    $updateBalance->execute();

// 9. Commit transactie
$conn->commit();

// 10. Winkelmandje leegmaken
Cart::clear();

// Redirect naar succespagina
header("Location: checkout_success.php?amount=" . urlencode($total));
exit;


} catch (Exception $e) {
    $conn->rollBack();
    echo "<p style='text-align:center; color:red;'>Er ging iets mis: " . htmlspecialchars($e->getMessage()) . "</p>";
}
