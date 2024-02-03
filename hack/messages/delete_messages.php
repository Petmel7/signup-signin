<?php
require_once '../actions/helpers.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримати дані з POST-запиту
    $messageId = $data['message_id'] ?? null;

    if ($messageId !== null) {
        try {
            $conn = getPDO();

            // Викликати функцію для видалення повідомлення з бази даних
            $sql = "DELETE FROM `messages` WHERE id = :message_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->execute();

            // Повернути успішний результат
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
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
        // Обробка помилок, якщо не отримано ідентифікатор повідомлення
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    // Обробка помилок, якщо запит не є POST-запитом
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
}





// $data = json_decode(file_get_contents("php://input"), true);

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Отримати дані з POST-запиту
//     $messageId = $data['id'] ?? null;

//     if ($messageId !== null) {
//         try {
//             $conn = getPDO();

//             // Викликати функцію для видалення повідомлення з бази даних
//             $sql = "DELETE FROM `messages` WHERE sender_id = :sender_id AND id = :id";
//             $stmt = $conn->prepare($sql);
//             $stmt->bindParam(':sender_id', $messageId, PDO::PARAM_INT);
//             $stmt->bindParam(':id', $messageId, PDO::PARAM_INT);
//             $stmt->execute();

//             // Повернути успішний результат
//             header('Content-Type: application/json');
//             echo json_encode(['success' => true]);
//         } catch (PDOException $e) {
//             // Обробка помилок бази даних
//             header('Content-Type: application/json');
//             echo json_encode(['error' => $e->getMessage()]);
//         } finally {
//             if ($conn !== null) {
//                 $conn = null;
//             }
//         }
//     } else {
//         // Обробка помилок, якщо не отримано ідентифікатор повідомлення
//         header('Content-Type: application/json');
//         echo json_encode(['error' => 'Invalid request']);
//     }
// } else {
//     // Обробка помилок, якщо запит не є POST-запитом
//     header('Content-Type: application/json');
//     echo json_encode(['error' => 'Invalid request method']);
// }
var_dump($messageId);
