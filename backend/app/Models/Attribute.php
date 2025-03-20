<?php

namespace App\Models;


use App\Core\Interfaces\AttributeInterface;
use App\Core\Model\Model;
use PDO;

class Attribute extends Model implements AttributeInterface
{
    protected string $table = 'attributes';

    public function getValues(): array
    {
        $stmt = $this->pdo->prepare(
            "
            SELECT a.name, JSON_ARRAYAGG(pa.value) AS values
            FROM attributes a
            LEFT JOIN product_attributes pa ON a.id = pa.attribute_id
            WHERE a.id = :id
        "
        );
        $stmt->execute(['id' => $this->id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}