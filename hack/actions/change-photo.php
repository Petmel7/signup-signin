<?php

require_once __DIR__ . '/helpers.php';

if (isset($_SESSION['user']['id'])) {

    $conn = getPDO();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
        $avatar = $_FILES["avatar"];

        $userId = $_SESSION['user']['id'];

        $targetPath = uploadFile($avatar, "avatar");

        $updateQuery = "UPDATE users SET avatar = '$targetPath' WHERE id = $userId";

        if ($conn->query($updateQuery) === TRUE) {
            echo "Photo updated successfully.";
        } else {
            echo
            "Error updating photo " . implode(", ", $conn->errorInfo());
        }
    }

    $conn = null;
} else {
    echo "User session not found.";
}

$baseUrl = '/signup-signin';
redirect($baseUrl . '/index.php?page=home');




// require_once __DIR__ . '/helpers.php';

// if (isset($_SESSION['user']['id'])) {

//     $conn = getPDO();

//     if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
//         // Отримати поточне ім'я файла зображення користувача
//         $userId = $_SESSION['user']['id'];
//         $selectQuery = "SELECT avatar FROM users WHERE id = $userId";
//         $result = $conn->query($selectQuery);
//         $currentImageName = $result->fetchColumn();

//         // Видалити попереднє зображення, якщо воно існує
//         $uploadPath = __DIR__ . '/../uploads';
//         $currentImagePath = "{$uploadPath}/{$currentImageName}";

//         if (file_exists($currentImagePath)) {
//             unlink($currentImagePath);
//         }

//         // Завантажити нове зображення
//         $avatar = $_FILES["avatar"];
//         $targetPath = uploadFile($avatar, "avatar");

//         // Оновити ім'я файла в базі даних
//         $updateQuery = "UPDATE users SET avatar = '$targetPath' WHERE id = $userId";

//         if ($conn->query($updateQuery) === TRUE) {
//             echo "Photo updated successfully.";
//         } else {
//             echo "Error updating photo";
//         }
//     }

//     $conn = null;
// } else {
//     echo "User session not found.";
// }

// $baseUrl = '/signup-signin';
// redirect($baseUrl . '/index.php?page=home');
