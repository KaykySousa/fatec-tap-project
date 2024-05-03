<?php
require_once "./vendor/autoload.php";

use App\Database\Database;
use App\UserRepository;

$database = Database::getInstance();
$connection = $database->getConnection();

$userRepository = new UserRepository($connection);

//HEADERS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// GET /users
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/users') {
    $users = $userRepository->findAll();
    echo json_encode($users);
}

// GET /users/:id
if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('/^\/users\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = $matches[1];
    $user = $userRepository->findById($id);

    if ($user === null) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        return;
    }

    echo json_encode($user);
}

// POST /users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/users') {
    $data = json_decode(file_get_contents('php://input'), true);

    $user = new App\User();
    $user->name = ($data['name']);
    $user->email = ($data['email']);
    $user->birthDate = ($data['birthDate']);

    $user = $userRepository->save($user);


    echo json_encode($user);
}

// PUT /users/:id
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && preg_match('/^\/users\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = $matches[1];
    $data = json_decode(file_get_contents('php://input'), true);

    $user = $userRepository->findById($id);

    if ($user === null) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        return;
    }

    $user->name = ($data['name']);
    $user->email = ($data['email']);
    $user->birthDate = ($data['birthDate']);

    $userRepository->update($user);

    echo json_encode($user);
}

// DELETE /users/:id
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && preg_match('/^\/users\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = $matches[1];
    $user = $userRepository->findById($id);

    if ($user === null) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        return;
    }

    $userRepository->delete($user->id);

    echo json_encode(['message' => 'User deleted']);
}
