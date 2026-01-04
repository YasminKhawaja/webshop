<?php
require_once __DIR__ . '/classes/Cart.php';

if (isset($_GET['id'])) {
    $key = $_GET['id'];          // GEEN (int) hier
    Cart::removeProduct($key);
}

header('Location: winkelwagen.php'); // juiste bestandsnaam van je cart-pagina
exit;
