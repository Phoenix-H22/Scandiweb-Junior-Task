<?php
namespace App\Core\Interfaces;

interface CategoryInterface {
    public function findById(int $id): ?array;
    public function findAll(): array;
    public function getProducts(): array;
}