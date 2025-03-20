<?php

namespace App\Core\Interfaces;

interface ProductInterface
{
    public function findById(string $id): ?array;

    public function findAllByCategory(?int $categoryId = null): array;

    public function createProduct(array $productData): string;

    public function updateProduct(string $id, array $productData): bool;

    public function deleteProduct(string $id): bool;

    public function getProductDetails(string $id): ?array;
}