<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/DB.php';

class Category
{
    public string $id;
    public string $name;
    public ?string $parent;

    // Optional: A method to fetch a category by its ID
    public static function getCategoryById(string $id): ?array
    {
        $db   = DB::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute([':id' => $id]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result === false ? null : $result;
    }
}
