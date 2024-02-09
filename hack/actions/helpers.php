<?php

session_start();

require_once __DIR__ . '/config.php';

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

function currentUserId(): int|bool
{
    $pdo = getPDO();

    if (!isset($_SESSION['user'])) {
        return false;
    }

    $userId = $_SESSION['user']['id'] ?? null;

    return $userId;
}

function getUserDataByUsername($username): ?array
{
    $conn = getPDO();

    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$username]);

    // Перевірка, чи існують дані для витягнення
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($userData === false) {
        return null;
    }

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

function getSubscriptions($user_id)
{
    try {
        $conn = getPDO();

        // Отримати список користувачів, на які підписаний конкретний користувач
        $sql = "SELECT users.* FROM users
                INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
                WHERE subscriptions.subscriber_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Вивести результат в форматі JSON
        return $results;
    } catch (PDOException $e) {
        // Обробка помилок бази даних
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

function getSubscribers($user_id)
{
    try {
        $conn = getPDO();

        // Отримати список підписників для конкретного користувача
        $sql = "SELECT users.* FROM users
                INNER JOIN subscriptions ON users.id = subscriptions.subscriber_id
                WHERE subscriptions.target_user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {
        // Обробка помилок бази даних
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

// function getMessagesBysenderId($senderId)
// {
//     try {
//         $conn = getPDO();

//         // SQL-запит для отримання повідомлень, адресованих конкретному користувачеві як відправник або отримувач
//         $sql = "SELECT * FROM messages WHERE sender_id = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->execute([$senderId]);

//         // Отримати результат запиту
//         $senderById = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         return $senderById;
//     } catch (PDOException $e) {
//         // Обробка помилок бази даних
//         return 'Error: ' . $e->getMessage();
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }
