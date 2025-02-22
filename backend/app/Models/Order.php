<?php
namespace App\Models;

use App\Core\Model\AbstractOrder;
use PDO;

class Order extends AbstractOrder {
    protected string $table = 'orders';

    public function createOrder(array $data): string
    {
        $this->pdo->beginTransaction();
        try {

            $productIds = array_map(fn($item) => $item['productId'], $data['items']);
            $productIdsStr = implode(',', array_map(fn($id) => "'$id'", $productIds));

            $stmt = $this->pdo->query("
                SELECT product_id, amount, currency_label 
                FROM prices 
                WHERE product_id IN ($productIdsStr)
            ");
            $prices = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $priceMap = [];
            foreach ($prices as $price) {
                $priceMap[$price['product_id']] = [
                    'amount' => $price['amount'],
                    'currency' => $price['currency_label']
                ];
            }


            $calculatedTotal = 0;
            foreach ($data['items'] as $item) {
                if (!isset($priceMap[$item['productId']])) {
                    return "Product price not found for ID: {$item['productId']}";
                }
                $calculatedTotal += $priceMap[$item['productId']]['amount'] * $item['quantity'];
            }

            if (abs($calculatedTotal - $data['total']) > 0.01) {
                return "Total amount mismatch. Expected: $calculatedTotal, Received: {$data['total']}";
            }


            $stmt = $this->pdo->prepare("
                INSERT INTO orders (total_amount, currency) 
                VALUES (:total_amount, :currency)
            ");
            $stmt->execute([
                'total_amount' => $calculatedTotal,
                'currency' => $data['currency']
            ]);
            $orderId = $this->pdo->lastInsertId();

            $itemStmt = $this->pdo->prepare("
                INSERT INTO order_items (
                    order_id, product_id, quantity, price, currency
                ) VALUES (
                    :order_id, :product_id, :quantity, :price, :currency
                )
            ");

            $attrStmt = $this->pdo->prepare("
                INSERT INTO order_item_attributes (
                    order_item_id, attribute_name, selected_value
                ) VALUES (
                    :order_item_id, :attribute_name, :selected_value
                )
            ");

            foreach ($data['items'] as $item) {
                try {

                    $itemStmt->execute([
                        'order_id' => $orderId,
                        'product_id' => $item['productId'],
                        'quantity' => $item['quantity'],
                        'price' => $priceMap[$item['productId']]['amount'],
                        'currency' => $priceMap[$item['productId']]['currency']
                    ]);
                    $orderItemId = $this->pdo->lastInsertId();

                    if (!empty($item['attributes'])) {
                        foreach ($item['attributes'] as $attr) {
                            $attrStmt->execute([
                                'order_item_id' => $orderItemId,
                                'attribute_name' => $attr['name'],
                                'selected_value' => $attr['value']
                            ]);
                        }
                    }
                } catch (\PDOException $e) {
                    $this->pdo->rollBack();
                    return "Failed to create order: " . $e->getMessage();
                }
            }

            $this->pdo->commit();
            return (string) $orderId;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return "Failed to create order: " . $e->getMessage();
        }
    }

    public function getOrderProductDetails(int $orderId): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                p.id, p.name, p.description, p.brand,
                oi.quantity,
                COALESCE(
                    JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'amount', pr.amount,
                            'currency_label', pr.currency_label,
                            'currency_symbol', pr.currency_symbol
                        )
                    ), '[]'
                ) AS prices,
                (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'name', oia.attribute_name,
                            'selected_value', oia.selected_value
                        )
                    )
                    FROM order_item_attributes oia
                    WHERE oia.order_item_id = oi.id
                ) AS attributes
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN prices pr ON p.id = pr.product_id
            WHERE oi.order_id = :order_id
            GROUP BY p.id, p.name, p.description, p.brand, oi.id, oi.quantity
        ");

        $stmt->execute(['order_id' => $orderId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['prices'] = json_decode($product['prices'] ?? '[]', true);
            $product['attributes'] = json_decode($product['attributes'] ?? '[]', true);
        }

        return $products;
    }
    public function findAll(): array {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    o.id,
                    o.total_amount,
                    o.currency,
                    o.created_at
                FROM orders o
                ORDER BY o.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            error_log("Error fetching orders: " . $e->getMessage());
            throw $e;
        }
    }
}