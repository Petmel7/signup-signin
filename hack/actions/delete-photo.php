<?php
require_once __DIR__ . '/helpers.php';

if (isset($_SESSION['user']['id'])) {
    $conn = getPDO();

    $userId = $_SESSION['user']['id'];

    $defaultImagePath = 'uploads/avatar_4367658739.jpg'; // Замініть це значення на ваш шлях за замовчуванням

    $updateQuery = "UPDATE users SET avatar = '$defaultImagePath' WHERE id = $userId";

    if ($conn->query($updateQuery) === TRUE) {
        echo "Photo deleted successfully.";
    } else {
        echo "Error deleting photo " . implode(", ", $conn->errorInfo());
    }

    $conn = null;
} else {
    echo "User session not found.";
}

$baseUrl = '/signup-signin';
redirect($baseUrl . '/index.php?page=home');
