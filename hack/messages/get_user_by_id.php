<?php

require_once '../actions/helpers.php';

// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     // Отримати дані з GET-запиту
//     $userId = $_GET['id'] ?? null;

//     if ($userId !== null) {
//         try {
//             $conn = getPDO();

//             // Отримати інформацію про користувача за його ідентифікатором
//             $sql = "SELECT name, avatar FROM users WHERE id = ?";
//             $stmt = $conn->prepare($sql);
//             $stmt->bindParam(1, $userId, PDO::PARAM_INT);
//             $stmt->execute();

//             $user = $stmt->fetch(PDO::FETCH_ASSOC);

//             // Повернути результат у форматі JSON
//             header('Content-Type: application/json');
//             echo json_encode($user);
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
//         // Обробка помилок, якщо не отримано ідентифікатор користувача
//         header('Content-Type: application/json');
//         echo json_encode(['error' => 'Invalid request']);
//     }
// } else {
//     // Обробка помилок, якщо запит не є GET-запитом
//     header('Content-Type: application/json');
//     echo json_encode(['error' => 'Invalid request method']);
// }





// $data = json_decode(file_get_contents('php://input'), true);

// if (isset($data['id'])) {
//     $loggedInUserId = $data['id'];

//     $success = getLoggedInUserId($loggedInUserId);

//     header('Content-Type: application/json');
//     echo json_encode(['success' => $success]);
// } else {
//     echo json_encode(['error' => 'Invalid request']);
// }

// function getLoggedInUserId()
// {
//     try {
//         $conn = getPDO();

//         // Отримати інформацію про користувача за його ідентифікатором
//         $sql = "SELECT name, avatar FROM users WHERE id = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(1, $loggedInUserId, PDO::PARAM_INT);
//         $stmt->execute();

//         $user = $stmt->fetch(PDO::FETCH_ASSOC);
//     } catch (PDOException $e) {
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }






$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['sender_id'])) {
    $senderId = $data['sender_id'];

    $success = getLoggedInUser($recipientId);

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function getLoggedInUser($senderId)
{
    try {
        $conn = getPDO();

        // Отримати інформацію про користувача за його ідентифікатором
        $sql = "SELECT name, avatar FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $senderId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user; // Повернути інформацію про користувача
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()]; // Повернути помилку, якщо її було
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}
