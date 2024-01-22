<?php

session_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../../monolog-config.php';

function getPDO(): PDO
{
    try {
        return new \PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8;dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
    } catch (\PDOException $e) {
        die('Conection error: ' . $e->getMessage());
    }
}

function redirect(string $path)
{
    header(header: "location: $path");
    die();
}

function addValidationError(string $fieldName, string $message): void
{
    $_SESSION['validation'][$fieldName] = $message;
}

function hasValidationError(string $fieldName): bool
{
    return isset($_SESSION['validation'][$fieldName]);
}


function validationErrorAttr(string $fieldName): string
{
    return isset($_SESSION['validation'][$fieldName]) ? 'aria-invalid="true"' : '';
}

function validationErrorMessage(string $fieldName): string
{
    $message = $_SESSION['validation'][$fieldName] ?? '';
    unset($_SESSION['validation'][$fieldName]);
    return $message;
}

function addOldValue(string $key, mixed $value): void
{
    $_SESSION['old'][$key] = $value;
}

function old(string $key)
{
    $value = $_SESSION['old'][$key] ?? '';
    unset($_SESSION['old'][$key]);
    return $value;
}

function uploadFile(array $file, string $prefix = ''): string
{
    $uploadPath = __DIR__ . '/../uploads';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = $prefix . '_' . time() . ".$ext";

    if (!move_uploaded_file($file['tmp_name'], "{$uploadPath}/{$fileName}")) {
        die('Помилка при загрузці файла на сервер!');
    }

    return "uploads/$fileName";
}

function setMessage(string $key, string $message): void
{
    $_SESSION['message'][$key] = $message;
}

function hasMessage(string $key): bool
{
    return isset($_SESSION['message'][$key]);
}

function getMessage(string $key): string
{
    $message = $_SESSION['message'][$key] ?? '';
    unset($_SESSION['message'][$key]);
    return $message;
}

function findUser(string $email): array|bool
{
    $pdo = getPDO();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE `email` = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(mode: \PDO::FETCH_ASSOC);
}

function currentUser(): array|bool
{
    $pdo = getPDO();

    if (!isset($_SESSION['user'])) {
        return false;
    }

    $userId = $_SESSION['user']['id'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch(mode: \PDO::FETCH_ASSOC);
}

function getUserDataByUsername($username): array
{
    $conn = getPDO();

    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$username]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    return $userData;
}

function logout(): void
{
    unset($_SESSION['user']['id']);
}

function checkAuth(): void
{
    if (!isset($_SESSION['user']['id'])) {
        $baseUrl = '/signup-signin';
        redirect($baseUrl . '/index.php?page=signin');
    }
}

function checkGuest(): void
{
    if (isset($_SESSION['user']['id'])) {
        $baseUrl = '/signup-signin';
        redirect($baseUrl . '/index.php?page=home');
    }
}

function getLoggedInUsername(): string|null
{
    return isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
}

function handleGetRequest($loggedInUsername)
{
    try {
        $conn = getPDO();

        $sql = "SELECT name, avatar FROM users WHERE name <> ?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$loggedInUsername]);

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    } catch (PDOException $e) {

        return ['error' => $e->getMessage()];
    } finally {

        if ($conn !== null) {
            $conn = null;
        }
    }
}

function searchFriendsByName($name, $loggedInUsername)
{
    try {
        $conn = getPDO();
        $sql = "SELECT name, avatar FROM users WHERE name LIKE :search AND name <> :loggedInUsername";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {

        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

// var_dump($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
