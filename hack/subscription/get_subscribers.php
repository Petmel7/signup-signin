<?php
require_once '../actions/helpers.php';

// Отримати дані з AJAX-запиту
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_id'])) {
    $user_id = $data['user_id'];

    // Викликати функцію для отримання списку підписчиків з бази даних
    $subscribers = getSubscribers($user_id);

    // Вивести результат в форматі JSON
    header('Content-Type: application/json');
    echo json_encode($subscribers);
} else {
    // Обробка помилок, якщо не отримано всі необхідні дані з AJAX-запиту
    echo json_encode(['error' => 'Invalid request']);
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

// var_dump($user_id);
