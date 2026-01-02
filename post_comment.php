<?php
require_once(__DIR__ . "/classes/Database.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['comment-text'])) {
    $comment = trim($_POST['comment-text']);
    $productId = (int)$_POST['product_id'];
    $userId = $_SESSION['user_id'];

    $conn = Database::getConnection();

    // Controleren of de gebruiker dit product kocht
    $check = $conn->prepare("
        SELECT COUNT(*) 
        FROM webshop.product_ordered 
        WHERE User_ID = :userId AND Product_ID = :productId
    ");
    $check->bindValue(":userId", $userId);
    $check->bindValue(":productId", $productId);
    $check->execute();
    $hasBought = $check->fetchColumn() > 0;

    if ($hasBought) {
        $stmt = $conn->prepare("
            INSERT INTO webshop.Comments (Comment, User_ID, Product_ID)
            VALUES (:comment, :userId, :productId)
        ");
        $stmt->bindValue(":comment", htmlspecialchars($comment));
        $stmt->bindValue(":userId", $userId);
        $stmt->bindValue(":productId", $productId);
        $stmt->execute();

        header("Location: detailpagina.php?id=$productId&review=success");
        exit;
    } else {
        header("Location: detailpagina.php?id=$productId&review=forbidden");
        exit;
    }
}
?>
