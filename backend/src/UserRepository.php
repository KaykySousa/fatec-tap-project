<?php

namespace App;

use App\User;

class UserRepository {
    private \PDO $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    public function findAll(): array {
        $stmt = $this->connection->query('CALL select_all_usuarios()');

        $users = [];

        // Map rows to objects
        foreach ($stmt as $row) {
            $user = new User();
            $user->id = ($row['id']);
            $user->name = ($row['name']);
            $user->email = ($row['email']);
            $user->birthDate = ($row['birthdate']);
            $users[] = $user;
        }

        return $users;
    }

    public function findById(int $id): ?User {
        $stmt = $this->connection->prepare('CALL select_usuario(:id)');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch();


        if (!$row) {
            return null;
        }


        $user = new User();
        $user->id = ($row['id']);
        $user->name = ($row['name']);
        $user->email = ($row['email']);
        $user->birthDate = ($row['birthdate']);

        return $user;
    }

    public function save(User $user): User {
        $stmt = $this->connection->prepare('CALL insert_usuario(:name, :email, :birth_date)');
        $name = $user->name;
        $email = $user->email;
        $birth_date = $user->birthDate;
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birth_date', $birth_date);

        $stmt->execute();

        return $user;
    }

    public function update(User $user): User {
        $stmt = $this->connection->prepare('CALL update_usuario(:id, :name, :email, :birth_date)');
        $stmt->bindParam(':id', $user->id);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':birth_date', $user->birthDate);

        $stmt->execute();

        return $user;
    }

    public function delete(int $id): void {
        $stmt = $this->connection->prepare('CALL delete_usuario(:id)');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
