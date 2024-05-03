<?php

namespace App\Database;

class Config {
    public static function get(): array {
        return [
            'host' => '127.0.0.1',
            'dbname' => 'projetophp',
            'user' => 'root',
            'password' => '',
        ];
    }
}
