<?php

require_once 'helpers.php';

// Отримати дані з AJAX-запиту
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($_SESSION['user']['name'])) {
    $name = $data['name'];
    $loggedInUsername = $_SESSION['user']['name'];

    // Викликати функцію для пошуку в базі даних
    $results = searchFriendsByName($name, $loggedInUsername);

    // Вивести результати у форматі JSON
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    // Обробка помилок, якщо не отримано ім'я з AJAX-запиту або користувач не авторизований
    echo json_encode(['error' => 'Invalid request']);
}
