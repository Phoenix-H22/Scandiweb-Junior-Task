<?php
namespace App\Core\Model;

use App\Core\Database\Database;
use App\Core\Interfaces\ModelInterface;
use PDO;

abstract class Model implements ModelInterface {
    protected PDO $pdo;
    protected string $table;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM " . $this->table);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    protected function insert(array $data): string {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($values)");
        return $stmt->execute($data) ? $this->pdo->lastInsertId() : false;
    }

    protected function modify(string $id, array $data): bool {
        $updates = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $data['id'] = $id;

        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $updates WHERE id = :id");
        return $stmt->execute($data);
    }

    protected function remove(string $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}