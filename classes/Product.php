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
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Filterfunctie MET totaal-aantal voor paginatie ---
    public static function getFiltered(array $filters, int $limit, int $offset): array {
        $conn = Database::getConnection();

        $where = ["p.Is_Available = 1"];
        $params = [];

        // Merkfilter
        if (!empty($filters['brand'])) {
            $in = str_repeat('?,', count($filters['brand']) - 1) . '?';
            $where[] = "b.Brand IN ($in)";
            foreach ($filters['brand'] as $val) {
                $params[] = $val;
            }
        }

        // Categoriefilter
        if (!empty($filters['category'])) {
            $in = str_repeat('?,', count($filters['category']) - 1) . '?';
            $where[] = "c.Category IN ($in)";
            foreach ($filters['category'] as $val) {
                $params[] = $val;
            }
        }

        // Producttypefilter
        if (!empty($filters['producttype'])) {
            $in = str_repeat('?,', count($filters['producttype']) - 1) . '?';
            $where[] = "t.Type IN ($in)";
            foreach ($filters['producttype'] as $val) {
                $params[] = $val;
            }
        }

        // Cruelty Free filter
        if (isset($filters['crueltyfree']) && $filters['crueltyfree'] !== '') {
            $where[] = "p.Is_Cruelty_Free = ?";
            $params[] = (int)$filters['crueltyfree'];
        }

        $whereSql = '';
        if (!empty($where)) {
            $whereSql = 'WHERE ' . implode(' AND ', $where);
        }

        // 1) Totaal aantal producten met deze filters (zonder LIMIT)
        $countSql = "
            SELECT COUNT(*)
            FROM products p
            LEFT JOIN brands b ON p.Brand_ID = b.Brand_ID
            LEFT JOIN categories c ON p.Category_ID = c.Category_ID
            LEFT JOIN types t ON p.Type_ID = t.Type_ID
            $whereSql
        ";
        $countStmt = $conn->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // 2) Producten voor huidige pagina (met LIMIT/OFFSET)
        $dataSql = "
            SELECT 
                p.*, 
                b.Brand, 
                c.Category, 
                t.Type
            FROM products p
            LEFT JOIN brands b ON p.Brand_ID = b.Brand_ID
            LEFT JOIN categories c ON p.Category_ID = c.Category_ID
            LEFT JOIN types t ON p.Type_ID = t.Type_ID
            $whereSql
            ORDER BY p.Product_ID ASC
            LIMIT ? OFFSET ?
        ";

        $dataParams = $params;
        $dataParams[] = $limit;
        $dataParams[] = $offset;

        $stmt = $conn->prepare($dataSql);
        $stmt->execute($dataParams);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'products' => $products,
            'total'    => $total
        ];
    }

    // --- Filter voor admin ---
    public static function getFilteredAdmin(string $category = '', string $brand = '', string $search = ''): array {
        $conn = Database::getConnection();

        $query = "
            SELECT 
                p.*, b.Brand, c.Category, t.Type
            FROM products p
            LEFT JOIN brands b ON p.Brand_ID = b.Brand_ID
            LEFT JOIN categories c ON p.Category_ID = c.Category_ID
            LEFT JOIN types t ON p.Type_ID = t.Type_ID
            WHERE p.Is_Available = 1
        ";

        $params = [];

        if (!empty($category)) {
            $query .= " AND c.Category = ?";
            $params[] = $category;
        }

        if (!empty($brand)) {
            $query .= " AND b.Brand = ?";
            $params[] = $brand;
        }

        if (!empty($search)) {
            $query .= " AND (p.Product_Name LIKE ? OR p.Product_ID LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $query .= " ORDER BY p.Product_ID ASC";

        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Zoekfunctie (frontend searchbar) ---
    public static function search(string $term, int $limit = 20, int $offset = 0): array {
        $conn = Database::getConnection();
        $term = trim($term);

        $sql = "
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
              AND (
                    p.Product_Name LIKE ?
                 OR b.Brand        LIKE ?
                 OR c.Category     LIKE ?
                 OR t.Type         LIKE ?
                 OR p.Description  LIKE ?
              )
            ORDER BY p.Product_ID ASC
            LIMIT ? OFFSET ?
        ";

        $stmt = $conn->prepare($sql);
        $like = '%' . $term . '%';

        // 7 placeholders: 5x LIKE, limit, offset
        $stmt->execute([
            $like, $like, $like, $like, $like,
            $limit,
            $offset
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Aantal zoekresultaten tellen ---
    public static function countSearch(string $term): int {
        $conn = Database::getConnection();
        $term = trim($term);

        $sql = "
            SELECT COUNT(*)
            FROM products p
            LEFT JOIN brands b ON p.Brand_ID = b.Brand_ID
            LEFT JOIN categories c ON p.Category_ID = c.Category_ID
            LEFT JOIN types t ON p.Type_ID = t.Type_ID
            WHERE p.Is_Available = 1
              AND (
                    p.Product_Name LIKE ?
                 OR b.Brand        LIKE ?
                 OR c.Category     LIKE ?
                 OR t.Type         LIKE ?
                 OR p.Description  LIKE ?
              )
        ";

        $stmt = $conn->prepare($sql);
        $like = '%' . $term . '%';

        // 5 placeholders: 5x LIKE
        $stmt->execute([$like, $like, $like, $like, $like]);

        return (int)$stmt->fetchColumn();
    }
}
