<?php

namespace Core;

use Core\Database;
use PDO;

abstract class Model
{
    protected ?PDO $db;

    protected $table;
    protected $fillable = [];
    protected string $primaryKey = 'id';
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->db = Database::connect();
        $this->attributes = $attributes;
    }

    private function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    private function find(int $id): ?object
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new static($data) : null;
    }

    private function where(array $conditions): array
    {
        $query = "SELECT * FROM {$this->table} WHERE ";
        $queryParts = [];
        $params = [];

        foreach ($conditions as $key => $value) {
            $queryParts[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }

        $query .= implode(' AND ', $queryParts);
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    private function whereFirst(array $conditions): ?object
    {
        $results = $this->where($conditions);
        return $results[0] ?? null;
    }

    private function create(array $data): bool
    {
        $data = $this->filterFillable($data);
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})");
        return $stmt->execute($data);
    }

    public function update(array $data): bool
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            throw new \Exception("No primary key set for the model instance.");
        }

        $id = $this->attributes[$this->primaryKey];
        $data = $this->filterFillable($data);
        $fields = implode(', ', array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        $data['id'] = $id;

        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$fields} WHERE {$this->primaryKey} = :id");
        $updated = $stmt->execute($data);

        if ($updated) {
            $this->attributes = array_merge($this->attributes, $data);
        }

        return $updated;
    }

    public function delete(): bool
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            throw new \Exception("No primary key set for the model instance.");
        }

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $this->attributes[$this->primaryKey]]);
    }

    private function filterFillable(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public static function __callStatic($method, $args)
    {
        $instance = new static();

        if (method_exists($instance, $method)) {
            return $instance->$method(...$args);
        }

        throw new \BadMethodCallException("Method {$method} does not exist in " . static::class);
    }

    public function __destruct()
    {
        $this->db = null;
    }
}

// EOF
