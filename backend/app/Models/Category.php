<?php

namespace App\Models;

use App\Core\Interfaces\CategoryInterface;
use App\Core\Model\Model;
use PDO;

class Category extends Model implements CategoryInterface
{
    protected string $table = 'categories';

    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query(
                "
                SELECT 
                    id,
                    name,
                    created_at
                FROM categories
                ORDER BY id ASC
            "
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            error_log("Error fetching categories: " . $e->getMessage());
            throw $e;
        }
    }

    public function findById($id): ?array
    {
        try {
            $stmt = $this->pdo->prepare(
                "
                SELECT 
                    id,
                    name,
                    created_at
                FROM categories
                WHERE id = :id
            "
            );
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (\Throwable $e) {
            error_log("Error fetching category: " . $e->getMessage());
            throw $e;
        }
    }

    public function getProducts(): array
    {
        try {
            $stmt = $this->pdo->prepare(
                "
                SELECT p.* 
                FROM products p
                WHERE p.category_id = :id
            "
            );
            $stmt->execute(['id' => $this->id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            error_log("Error fetching category products: " . $e->getMessage());
            throw $e;
        }
    }
}