<?php
require_once '../actions/helpers.php';

// Отримати дані з запиту
$user_id = $_GET['user_id'] ?? null;

if ($user_id !== null) {
    // Викликати функцію для отримання списку підписок користувача з бази даних
    $hisSubscribers = getSubscribers($user_id);

    // Вивести результат в форматі JSON
    header('Content-Type: application/json');
    echo json_encode($hisSubscribers);
} else {
    // Обробка помилок, якщо не отримано всі необхідні дані з AJAX-запиту
    echo json_encode(['error' => 'Invalid request']);
}
