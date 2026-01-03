<?php
session_start();
require_once(__DIR__ . "/classes/Database.php");

// Alleen admins mogen verwijderen
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin-login.php");
    exit;
}

// Check of er een ID is opgegeven
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin-dashboard.php?error=ongeldig_id");
    exit;
}

try {
    $conn = Database::getConnection();
    $productId = (int)$_GET['id'];

    // Controleren of product bestaat
    $check = $conn->prepare("SELECT Product_ID FROM products WHERE Product_ID = :id");
    $check->bindValue(":id", $productId, PDO::PARAM_INT);
    $check->execute();

    if ($check->rowCount() === 0) {
        throw new Exception("Product niet gevonden.");
    }

    // Soft delete: beschikbaarheid uitschakelen
    $stmt = $conn->prepare("
        UPDATE products
        SET Is_Available = 0,
            Updated_At = NOW()
        WHERE Product_ID = :id
    ");
    $stmt->bindValue(":id", $productId, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: admin-dashboard.php?success=verwijderd");
    exit;

} catch (Exception $e) {
    header("Location: admin-dashboard.php?error=" . urlencode($e->getMessage()));
    exit;
}
