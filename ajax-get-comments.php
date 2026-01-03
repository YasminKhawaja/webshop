<?php
require_once(__DIR__ . "/classes/Database.php");

header('Content-Type: application/json');

if (!isset($_GET['product_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Product ID ontbreekt"]);
    exit;
}

try {
    $conn = Database::getConnection();

    $stmt = $conn->prepare("
        SELECT 
            c.Comment, 
            c.Created_At, 
            u.First_Name, 
            u.Last_Name
        FROM comments c
        LEFT JOIN users u ON c.User_ID = u.User_ID
        WHERE c.Product_ID = :pid AND c.Is_Active = 1
        ORDER BY c.Created_At DESC
    ");
    $stmt->bindValue(":pid", (int)$_GET['product_id'], PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Databasefout: " . $e->getMessage()]);
}
