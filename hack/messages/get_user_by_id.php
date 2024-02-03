<?php

require_once '../actions/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Отримати дані з GET-запиту
    $userId = $_GET['id'] ?? null;

    if ($userId !== null) {
        try {
            $conn = getPDO();

            // Отримати інформацію про користувача за його ідентифікатором
            $sql = "SELECT name, avatar FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Повернути результат у форматі JSON
            header('Content-Type: application/json');
            echo json_encode($user);
        } catch (PDOException $e) {
            // Обробка помилок бази даних
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        } finally {
            if ($conn !== null) {
                $conn = null;
            }
        }
    } else {
        // Обробка помилок, якщо не отримано ідентифікатор користувача
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    // Обробка помилок, якщо запит не є GET-запитом
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
}
