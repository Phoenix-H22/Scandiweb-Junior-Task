<?php

namespace App\Core\Interfaces;

interface ModelInterface
{
    public function findAll(): array;

    public function findById($id): ?array;
}
