<?php

namespace App\Models;


use App\Core\Interfaces\ProductInterface;
use App\Core\Model\Model;
use PDO;

class Product extends Model implements ProductInterface
{
    protected string $table = 'products';

    public function findById($id): ?array
    {
        try {
            if (empty($id)) {
                throw new \InvalidArgumentException("Product ID cannot be empty");
            }

            $product = $this->fetchProductWithBasicRelations($id);
            if (!$product) {
                throw new \Exception("Product not found with ID: " . $id);
            }

            $attributes = $this->fetchProductAttributes($id);
            $gallery = $this->fetchProductGallery($id);

            return $this->formatProductResponse($product, $attributes, $gallery);
        } catch (\Throwable $e) {
            error_log("🚨 Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function findAllByCategory(?int $categoryId = null): array
    {
        try {
            error_log(
                "Fetching products" . ($categoryId ? " for category ID: $categoryId" : " for all categories") . "..."
            );

            $products = $this->fetchProductsWithCategory($categoryId);
            if (empty($products)) {
                error_log("No products found.");
                return [];
            }

            $attributes = $this->fetchAllProductAttributes();
            $galleries = $this->fetchAllProductGalleries();

            return $this->formatProductsResponse($products, $attributes, $galleries);
        } catch (\Throwable $e) {
            error_log("Database Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getProductDetails(string $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("Product ID is not set.");
        }

        $product = $this->fetchProductWithPrices($id);
        return $product ? $this->formatBasicProduct($product) : null;
    }

    public function createProduct(array $productData): string
    {
        try {
            return $this->insert($productData);
        } catch (\Throwable $e) {
            error_log("Error creating product: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateProduct(string $id, array $productData): bool
    {
        try {
            return $this->modify($id, $productData);
        } catch (\Throwable $e) {
            error_log("Error updating product: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteProduct(string $id): bool
    {
        try {
            return $this->remove($id);
        } catch (\Throwable $e) {
            error_log("Error deleting product: " . $e->getMessage());
            throw $e;
        }
    }

    private function fetchProductWithBasicRelations(string $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "
            SELECT 
                p.*,
                c.id as category_id,
                c.name as category_name,
                COALESCE(
                    JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'amount', pr.amount,
                            'currency_label', pr.currency_label,
                            'currency_symbol', pr.currency_symbol
                        )
                    ), '[]'
                ) AS prices
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN prices pr ON p.id = pr.product_id
            WHERE p.id = :id
            GROUP BY p.id, c.id, c.name
        "
        );

        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function fetchProductAttributes(string $id): array
    {
        $stmt = $this->pdo->prepare(
            "
            SELECT 
                a.name as attribute_name,
                GROUP_CONCAT(pa.value) as attribute_values
            FROM product_attributes pa
            JOIN attributes a ON pa.attribute_id = a.id
            WHERE pa.product_id = :id
            GROUP BY a.id, a.name
        "
        );

        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchProductGallery(string $id): array
    {
        $stmt = $this->pdo->prepare(
            "
            SELECT image_url
            FROM product_gallery
            WHERE product_id = :id
        "
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function fetchProductsWithCategory(?int $categoryId): array
    {
        $sql = "
            SELECT 
                p.*,
                c.id as category_id,
                c.name as category_name,
                COALESCE(
                    JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'amount', pr.amount,
                            'currency_label', pr.currency_label,
                            'currency_symbol', pr.currency_symbol
                        )
                    ), '[]'
                ) AS prices
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN prices pr ON p.id = pr.product_id
            WHERE (:categoryId IS NULL OR :categoryId = 1 OR c.id = :categoryId)
            GROUP BY p.id, c.id, c.name
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':categoryId', $categoryId, $categoryId !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchAllProductAttributes(): array
    {
        return $this->pdo->query(
            "
            SELECT 
                pa.product_id,
                a.name as attribute_name,
                GROUP_CONCAT(pa.value) as attribute_values
            FROM product_attributes pa
            JOIN attributes a ON pa.attribute_id = a.id
            GROUP BY pa.product_id, a.id, a.name
        "
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchAllProductGalleries(): array
    {
        return $this->pdo->query(
            "
            SELECT product_id, image_url
            FROM product_gallery
        "
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchProductWithPrices(string $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "
            SELECT p.*, 
                   JSON_ARRAYAGG(
                       JSON_OBJECT(
                           'amount', pr.amount,
                           'currency_label', pr.currency_label,
                           'currency_symbol', pr.currency_symbol
                       )
                   ) AS prices
            FROM products p
            LEFT JOIN prices pr ON p.id = pr.product_id
            WHERE p.id = :id
            GROUP BY p.id
        "
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function formatProductResponse(array $product, array $attributes, array $gallery): array
    {
        $formattedAttributes = array_map(function ($attr) {
            return [
                'name' => $attr['attribute_name'],
                'values' => explode(',', $attr['attribute_values'])
            ];
        }, $attributes);

        return [
            'id' => (string)$product['id'],
            'name' => (string)$product['name'],
            'category' => [
                'id' => (int)$product['category_id'],
                'name' => (string)$product['category_name']
            ],
            'description' => (string)$product['description'],
            'brand' => (string)$product['brand'],
            'in_stock' => (bool)$product['in_stock'],
            'prices' => is_string($product['prices']) ? json_decode($product['prices'], true) : [],
            'attributes' => $formattedAttributes,
            'gallery' => $gallery ?? [],
            'created_at' => (string)$product['created_at']
        ];
    }

    private function formatProductsResponse(array $products, array $attributes, array $galleries): array
    {
        $productAttributes = $this->groupAttributesByProduct($attributes);
        $productGalleries = $this->groupGalleriesByProduct($galleries);

        return array_map(function ($product) use ($productAttributes, $productGalleries) {
            $productId = $product['id'];
            return [
                'id' => (string)$productId,
                'name' => (string)$product['name'],
                'category' => [
                    'id' => (int)$product['category_id'],
                    'name' => (string)$product['category_name']
                ],
                'description' => (string)$product['description'],
                'brand' => (string)$product['brand'],
                'in_stock' => (bool)$product['in_stock'],
                'prices' => is_string($product['prices']) ? json_decode($product['prices'], true) : [],
                'attributes' => $productAttributes[$productId] ?? [],
                'gallery' => $productGalleries[$productId] ?? [],
                'created_at' => (string)$product['created_at']
            ];
        }, $products);
    }

    private function formatBasicProduct(array $product): array
    {
        return [
            'id' => (string)$product['id'],
            'name' => (string)$product['name'],
            'category' => [
                'id' => (int)$product['category_id'],
                'name' => (string)$product['category_name']
            ],
            'description' => (string)$product['description'],
            'brand' => (string)$product['brand'],
            'in_stock' => (bool)$product['in_stock'],
            'prices' => is_string($product['prices']) ? json_decode($product['prices'], true) : [],
            'attributes' => is_string($product['attributes']) ? json_decode($product['attributes'], true) : [],
            'created_at' => (string)$product['created_at']
        ];
    }

    private function groupAttributesByProduct(array $attributes): array
    {
        $grouped = [];
        foreach ($attributes as $attr) {
            $productId = $attr['product_id'];
            if (!isset($grouped[$productId])) {
                $grouped[$productId] = [];
            }
            $grouped[$productId][] = [
                'name' => $attr['attribute_name'],
                'values' => explode(',', $attr['attribute_values'])
            ];
        }
        return $grouped;
    }

    private function groupGalleriesByProduct(array $galleries): array
    {
        $grouped = [];
        foreach ($galleries as $gallery) {
            $productId = $gallery['product_id'];
            if (!isset($grouped[$productId])) {
                $grouped[$productId] = [];
            }
            $grouped[$productId][] = $gallery['image_url'];
        }
        return $grouped;
    }
}