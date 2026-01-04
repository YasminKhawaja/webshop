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

    // Product toevoegen aan winkelmandje (met variant-ondersteuning)
    public static function addProduct(
        int $productId,
        string $name,
        float $price,
        int $quantity = 1,
        string $image = '',
        ?array $variant = null
    ): void {
        self::startSession();

        // Unieke sleutel per product + variant
        $key = $productId;
        if ($variant && isset($variant['Variant_ID'])) {
            $key = $productId . '_' . $variant['Variant_ID'];
        }

        // Basisprijs + eventuele extra prijs van de variant
        $finalPrice = $price;
        if ($variant && isset($variant['Extra_Price'])) {
            $finalPrice += (float)$variant['Extra_Price'];
        }

        if (isset($_SESSION['cart'][$key])) {
            // Bestaat al → verhoog de hoeveelheid
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$key] = [
                'product_id' => $productId,
                'name'       => htmlspecialchars($name),
                'price'      => $finalPrice,
                'quantity'   => $quantity,
                'image'      => $image,
                'variant'    => $variant ? [
                    'id'    => $variant['Variant_ID'],
                    'name'  => $variant['Variant_Name'],
                    'extra' => (float)$variant['Extra_Price']
                ] : null
            ];
        }
    }

    // Product verwijderen
    public static function removeProduct(string $key): void {
        self::startSession();
        if (isset($_SESSION['cart'][$key])) {
            unset($_SESSION['cart'][$key]);
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
}
