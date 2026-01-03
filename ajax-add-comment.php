<?php
session_start();
require_once(__DIR__ . "/classes/Database.php");

header('Content-Type: application/json');

// --- Controle login ---
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Je moet ingelogd zijn om een review te plaatsen."]);
    exit;
}

// --- Validatie ---
if (empty($_POST['product_id']) || empty($_POST['comment'])) {
    echo json_encode(["error" => "Product of comment ontbreekt."]);
    exit;
}

$productId = (int)$_POST['product_id'];
$userId = (int)$_SESSION['user_id'];
$comment = trim($_POST['comment']);

try {
    $conn = Database::getConnection();

    // Check of user product gekocht heeft
    $check = $conn->prepare("
        SELECT COUNT(*) 
        FROM webshop.product_ordered po
        JOIN webshop.orders o ON po.Order_ID = o.Order_ID
        WHERE o.User_ID = :uid AND po.Product_ID = :pid
    ");
    $check->bindValue(":uid", $userId, PDO::PARAM_INT);
    $check->bindValue(":pid", $productId, PDO::PARAM_INT);
    $check->execute();

    if ($check->fetchColumn() == 0) {
        echo json_encode(["error" => "Je kunt alleen een review plaatsen voor producten die je hebt gekocht."]);
        exit;
    }

    // Voeg comment toe
    $stmt = $conn->prepare("
        INSERT INTO comments (Comment, User_ID, Product_ID, Is_Active)
        VALUES (:comment, :uid, :pid, 1)
    ");
    $stmt->bindValue(":comment", $comment);
    $stmt->bindValue(":uid", $userId, PDO::PARAM_INT);
    $stmt->bindValue(":pid", $productId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["error" => "Databasefout: " . $e->getMessage()]);
}
