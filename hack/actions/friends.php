<?php

require_once __DIR__ . '/helpers.php';

// Отримуємо з'єднання з базою даних
$conn = getPDO();

// Ваш SQL-запит для отримання даних з бази даних
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Масив для збереження результатів запиту
$users = array();

// Перевіряємо, чи є результат
if ($result !== false) {
    // Використовуємо метод rowCount(), оскільки у PDOStatement немає властивості num_rows
    $rowCount = $result->rowCount();

    if ($rowCount > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Додаємо дані кожного користувача до масиву
            $users[] = array(
                'name' => $row['name'],
                'avatar' => $row['avatar'],
                // Додайте інші поля, які вам потрібні
            );
        }
    }
}

// Виводимо дані у форматі JSON (можете використовувати інший формат, якщо потрібно)
header('Content-Type: application/json');
echo json_encode($users);

// Закриваємо з'єднання з базою даних
$conn = null; // Закриваємо з'єднання

// $baseUrl = '/signup-signin';
// redirect($baseUrl . '/index.php?page=home');
