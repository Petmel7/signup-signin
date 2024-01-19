<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../../monolog-config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $loggedInUsername = getLoggedInUsername();

    handleGetRequest($loggedInUsername, $log);
}

function handleGetRequest($loggedInUsername, $log)
{
    try {
        // Отримуємо з'єднання з базою даних
        $conn = getPDO();

        // Ваш SQL-запит для отримання даних з бази даних
        $sql = "SELECT name, avatar FROM users WHERE name <> ?";

        // Використовуємо подготовлений запит
        $stmt = $conn->prepare($sql);
        $stmt->execute([$loggedInUsername]);

        // $log->warning('warning $loggedInUsername', [$loggedInUsername]);
        // $log->error('error $loggedInUsername', [$loggedInUsername]);

        // Отримуємо всі рядки разом
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users) {
            header('Content-Type: application/json');
            echo json_encode($users);
        } else {
            echo json_encode(['error' => 'No users found.']);
        }
    } catch (PDOException $e) {
        // Обробка помилок бази даних
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        // Закриваємо з'єднання з базою даних у будь-якому випадку
        if ($conn !== null) {
            $conn = null;
        }
    }
}

handleGetRequest($loggedInUsername, $log);

var_dump($loggedInUsername);
