
<?php

require_once '../actions/helpers.php';

// Отримати дані з AJAX-запиту
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['user_id'])) {
    $user_id = $data['user_id'];

    // Викликати функцію для отримання списку підписок користувача з бази даних
    $subscriptions = getSubscriptions($user_id);

    // Вивести результат в форматі JSON
    header('Content-Type: application/json');
    echo json_encode($subscriptions);
} else {
    // Обробка помилок, якщо не отримано всі необхідні дані з AJAX-запиту
    echo json_encode(['error' => 'Invalid request']);
}
