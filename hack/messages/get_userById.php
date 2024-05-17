<?php
require_once '../actions/helpers.php';

// // Отримати значення параметра 'id' з URL
// $userId = $_GET['id'] ?? null;

// // Перевірити, чи було передано значення 'id'
// if ($userId !== null) {
//     // Викликати функцію getUserById з переданим значенням $userId
//     $user = getUserById($userId);

//     // Вивести результат у форматі JSON
//     header('Content-Type: application/json');
//     echo json_encode($user);
// } else {
//     // Вивести помилку, якщо значення 'id' не було передано
//     echo json_encode(['error' => 'User ID is missing']);
// }

// function getUserById($userId)
// {
//     try {
//         $conn = getPDO(); // Функція для отримання з'єднання з базою даних

//         $sql = "SELECT * FROM users WHERE id = :userId"; // Припустимо, що у вас є поле 'id' для ідентифікатора користувача
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
//         $stmt->execute();

//         $user = $stmt->fetch(PDO::FETCH_ASSOC);

//         return $user;
//     } catch (PDOException $e) {
//         // Обробка помилок бази даних
//         return ['error' => 'Database error: ' . $e->getMessage()];
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }
