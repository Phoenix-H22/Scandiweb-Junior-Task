<?php

namespace App\Core\Interfaces;

interface OrderInterface
{
    public function findAll(): array;

    public function createOrder(array $data): string;

    public function getOrderProductDetails(int $orderId): array;
}