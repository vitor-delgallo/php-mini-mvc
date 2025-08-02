<?php
namespace App\Models;

use System\Core\Database;

class User {
    public static function all(): array {
        //return Database::select("SELECT id, name, email FROM users");
        return [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@gmail.com'
            ], [
                'id' => 2,
                'name' => 'Jane Doe',
                'email' => 'jane.doe@gmail.com'
            ], [
                'id' => 3,
                'name' => 'Coby Doe',
                'email' => 'coby.doe@gmail.com'
            ]
        ];
    }

    public static function find(int $id): ?array {
        //return Database::selectRow("SELECT id, name, email FROM users WHERE id = :id", ['id' => $id]);
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@gmail.com'],
            ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane.doe@gmail.com'],
            ['id' => 3, 'name' => 'Coby Doe', 'email' => 'coby.doe@gmail.com'],
        ];
        $ret = array_values(array_filter($users, fn($u) => $u['id'] === $id));
        return $ret[0] ?? array();
    }

    public static function create(array $data): bool {
        /*return Database::statement(
            "INSERT INTO users (name, email) VALUES (:name, :email)",
            ['name' => $data['name'], 'email' => $data['email']]
        );*/
        return true;
    }
}