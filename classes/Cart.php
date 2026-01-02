<?php
class Cart {

    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Product toevoegen aan winkelmandje
    public static function addProduct(int $productId, string $name, float $price, int $quantity = 1): void {
        self::startSession();

        if (isset($_SESSION['cart'][$productId])) {
            // Bestaat al → verhoog de hoeveelheid
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => htmlspecialchars($name),
                'price' => $price,
                'quantity' => $quantity
            ];
        }
    }

    // Product verwijderen
    public static function removeProduct(int $productId): void {
        self::startSession();
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    // Mandje leegmaken
    public static function clear(): void {
        self::startSession();
        $_SESSION['cart'] = [];
    }

    // Alle producten ophalen
    public static function getCart(): array {
        self::startSession();
        return $_SESSION['cart'];
    }

    // Totaalbedrag berekenen
    public static function getTotal(): float {
        self::startSession();
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // Aantal unieke producten in mandje
    // public static function getItemCount(): int {
    //     self::startSession();
    //     return count($_SESSION['cart']);
    // }
    
}