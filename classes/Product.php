<?php
require_once(__DIR__ . "/Database.php");

class Product {

    // --- Alle producten ophalen ---
    public static function getAll(): array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT 
                p.Product_ID,
                p.Product_Name,
                p.Description,
                p.Image,
                p.Price,
                p.Is_Cruelty_Free,
                b.Brand,
                c.Category,
                t.Type
            FROM products p
            LEFT JOIN brands b ON p.Brand_ID = b.Brand_ID
            LEFT JOIN categories c ON p.Category_ID = c.Category_ID
            LEFT JOIN types t ON p.Type_ID = t.Type_ID
            WHERE p.Is_Available = 1
            ORDER BY p.Product_ID ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Tellen hoeveel producten beschikbaar zijn ---
    public static function countAll(): int {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT COUNT(*) FROM products WHERE Is_Available = 1");
        return (int)$stmt->fetchColumn();
    }

    // --- Paginatie: een subset van producten ophalen ---
    public static function getPaginated(int $limit, int $offset): array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            SELECT 
                p.Product_ID, 
                p.Product_Name, 
                p.Description, 
                p.Image, 
                p.Price,
                b.Brand,
                c.Category,
                t.Type
            FROM products p
            LEFT JOIN brands b ON p.Brand_ID = b.Brand_ID
            LEFT JOIN categories c ON p.Category_ID = c.Category_ID
            LEFT JOIN types t ON p.Type_ID = t.Type_ID
            WHERE p.Is_Available = 1
            ORDER BY p.Product_ID ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Filterfunctie ---
    public static function getFiltered(array $filters, int $limit, int $offset): array {
        $conn = Database::getConnection();

        $query = "
            SELECT 
                p.*, 
                b.Brand, 
                c.Category, 
                t.Type
            FROM products p
            LEFT JOIN brands b ON p.Brand_ID = b.Brand_ID
            LEFT JOIN categories c ON p.Category_ID = c.Category_ID
            LEFT JOIN types t ON p.Type_ID = t.Type_ID
            WHERE p.Is_Available = 1
        ";

        $params = [];

        // --- Merkfilter ---
        if (!empty($filters['brand'])) {
            $query .= " AND b.Brand IN (" . str_repeat('?,', count($filters['brand']) - 1) . "?)";
            $params = array_merge($params, $filters['brand']);
        }

        // --- Categoriefilter ---
        if (!empty($filters['category'])) {
            $query .= " AND c.Category IN (" . str_repeat('?,', count($filters['category']) - 1) . "?)";
            $params = array_merge($params, $filters['category']);
        }

        // --- Producttypefilter ---
        if (!empty($filters['producttype'])) {
            $query .= " AND t.Type IN (" . str_repeat('?,', count($filters['producttype']) - 1) . "?)";
            $params = array_merge($params, $filters['producttype']);
        }

        // --- Cruelty Free filter ---
        if (isset($filters['crueltyfree']) && $filters['crueltyfree'] !== '') {
            $query .= " AND p.Is_Cruelty_Free = ?";
            $params[] = $filters['crueltyfree'];
        }

        // --- Sortering + paginatie ---
        $query .= " ORDER BY p.Product_ID ASC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
