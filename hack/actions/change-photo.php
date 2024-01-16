<?php

require_once __DIR__ . '/helpers.php';

// Перевіряємо, чи існує інформація про користувача в сесії
if (isset($_SESSION['user']['id'])) {
    // Отримуємо підключення до бази даних
    $conn = getPDO();

    // Перевіряємо, чи був надісланий файл
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
        $avatar = $_FILES["avatar"];

        // Оновлюємо шлях до фото в базі даних
        $userId = $_SESSION['user']['id']; // Припустимо, що у вас є змінна сесії для ідентифікації користувача

        // Викликаємо функцію для завантаження файлу
        $targetPath = uploadFile($avatar, "avatar");

        // Оновлюємо шлях до фото в базі даних
        $updateQuery = "UPDATE users SET avatar = '$targetPath' WHERE id = $userId";

        if ($conn->query($updateQuery) === TRUE) {
            echo "Photo updated successfully.";
        } else {
            // echo "Error updating photo: " . $conn->error;
        }
    }

    // Закриваємо з'єднання з базою даних
    $conn = null; // Закриваємо з'єднання, присвоюючи null
} else {
    echo "User session not found."; // Додайте необхідну обробку, якщо сесія користувача не знайдена
}

$baseUrl = '/signup-signin';
redirect($baseUrl . '/index.php?page=home');
