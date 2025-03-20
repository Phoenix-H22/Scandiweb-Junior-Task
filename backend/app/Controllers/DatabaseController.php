<?php

namespace App\Controllers;

use PDO;
use PDOException;

class DatabaseController
{
    public function seed(): void
    {
        $dsn = "mysql:host=localhost;dbname=scandiweb;charset=utf8mb4";
        $username = "root";
        $password = "";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            $pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        $jsonData = file_get_contents('data.json');
        $data = json_decode($jsonData, true);

        if (!$data || !isset($data['data'])) {
            die("Invalid JSON data!");
        }

        $data = $data['data'];

        $categoryStmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name) ON DUPLICATE KEY UPDATE name=name");

        foreach ($data['categories'] as $category) {
            $categoryStmt->execute(['name' => $category['name']]);
        }

        function getCategoryId($categoryName, $pdo)
        {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = :name LIMIT 1");
            $stmt->execute(['name' => $categoryName]);
            return $stmt->fetchColumn() ?: null;
        }

        function getAttributeId($attributeName, $pdo)
        {
            $stmt = $pdo->prepare("SELECT id FROM attributes WHERE name = :name LIMIT 1");
            $stmt->execute(['name' => $attributeName]);
            return $stmt->fetchColumn();
        }

        $productStmt = $pdo->prepare(
            "
    INSERT INTO products (id, name, description, in_stock, category_id, brand) 
    VALUES (:id, :name, :description, :in_stock, :category_id, :brand)
    ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), in_stock=VALUES(in_stock), brand=VALUES(brand)
"
        );

        $priceStmt = $pdo->prepare(
            "
    INSERT INTO prices (product_id, amount, currency_label, currency_symbol) 
    VALUES (:product_id, :amount, :currency_label, :currency_symbol)
    ON DUPLICATE KEY UPDATE amount=VALUES(amount), currency_label=VALUES(currency_label), currency_symbol=VALUES(currency_symbol)
"
        );

        $attributeStmt = $pdo->prepare(
            "
    INSERT INTO attributes (name, type) VALUES (:name, :type)
"
        );

        $attrValueStmt = $pdo->prepare(
            "
    INSERT INTO product_attributes (product_id, attribute_id, value) 
    VALUES (:product_id, :attribute_id, :value)
    ON DUPLICATE KEY UPDATE value=value
"
        );

        foreach ($data['products'] as $product) {
            $categoryId = getCategoryId($product['category'], $pdo);

            $productStmt->execute([
                'id' => $product['id'],
                'name' => $product['name'],
                'description' => strip_tags($product['description']),
                'in_stock' => $product['inStock'] ? 1 : 0,
                'category_id' => $categoryId,
                'brand' => $product['brand']
            ]);

            foreach ($product['prices'] as $price) {
                $priceStmt->execute([
                    'product_id' => $product['id'],
                    'amount' => $price['amount'],
                    'currency_label' => $price['currency']['label'],
                    'currency_symbol' => $price['currency']['symbol']
                ]);
            }


            foreach ($product['attributes'] as $attribute) {
                $attributeId = getAttributeId($attribute['name'], $pdo);
                if (!$attributeId) {
                    $attributeStmt->execute([
                        'name' => $attribute['name'],
                        'type' => $attribute['type']
                    ]);
                    $attributeId = $pdo->lastInsertId();
                }


                foreach ($attribute['items'] as $item) {
                    $attrValueStmt->execute([
                        'product_id' => $product['id'],
                        'attribute_id' => $attributeId,
                        'value' => $item['value']
                    ]);
                }
            }
        }

        echo "✅ Database populated successfully (No Duplicate Attributes)!";
    }

    public function seedGalleryData(): void
    {
        $dsn = "mysql:host=localhost;dbname=scandiweb;charset=utf8mb4";
        $username = "root";
        $password = "";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            $pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }


        $jsonData = file_get_contents('data.json');
        $data = json_decode($jsonData, true);
        if (!$data) {
            die("Invalid JSON data!");
        }
        echo "<pre>";
        foreach ($data as $product) {
            if (isset($product['gallery']) && is_array($product['gallery'])) {
                foreach ($product['gallery'] as $imageUrl) {
                    $stmt = $pdo->prepare(
                        "INSERT INTO product_gallery (product_id, image_url) VALUES (:product_id, :image_url)"
                    );
                    $stmt->execute([
                        'product_id' => $product['id'],
                        'image_url' => $imageUrl
                    ]);
                }
            }
            var_dump($product, "<br>");
            var_dump(isset($product['gallery']));
        }

        echo "✅ Product gallery data inserted successfully.\n";
    }

}