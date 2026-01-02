<?php
require_once(__DIR__ . "/classes/Cart.php");

if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];
    Cart::removeProduct($productId);
}

header("Location: winkelwagen.php");
exit;
