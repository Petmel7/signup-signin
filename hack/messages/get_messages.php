<?php
require_once '../actions/helpers.php';

// try {
//     $conn = getPDO();

//     // Отримати повідомлення з бази даних
//     $sql = "SELECT messages.*, users.name as sender_name FROM messages
//             INNER JOIN users ON messages.sender_id = users.id
//             ORDER BY messages.sent_at DESC";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();

//     $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Вивести результат у форматі JSON
//     header('Content-Type: application/json');
//     echo json_encode($messages);
// } catch (PDOException $e) {
//     // Обробка помилок бази даних
//     echo json_encode(['error' => $e->getMessage()]);
// } finally {
//     if ($conn !== null) {
//         $conn = null;
//     }
// }



// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $recipientId = $_GET['recipient_id'] ?? null;

//     if ($userId !== null) {
//         try {
//             $conn = getPDO();

//             $stmt = $conn->prepare("SELECT * FROM messages WHERE recipient_id = ?");
//             $stmt->execute([$recipientId]);

//             $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

//             header('Content-Type: application/json');
//             echo json_encode($messages);
//         } catch (PDOException $e) {
//             // Обробка помилок бази даних
//             echo json_encode(['error' => $e->getMessage()]);
//         } finally {
//             if ($conn !== null) {
//                 $conn = null;
//             }
//         }
//     }
// }


// Отримати дані з AJAX-запиту
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient_id'])) {
    $recipientId = $data['recipient_id'];

    $success = getMessagesByRecipient($recipientId);

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function getMessagesByRecipient($recipientId)
{
    try {
        $conn = getPDO();

        // SQL-запит для отримання повідомлень, адресованих конкретному користувачеві
        $sql = "SELECT * FROM messages WHERE recipient_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$recipientId]);

        // Отримати результат запиту
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $messages;
    } catch (PDOException $e) {
        // Обробка помилок бази даних
        return 'Error: ' . $e->getMessage();
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

















// try {
//     $conn = getPDO();

//     // Отримати id поточного користувача
//     $loggedInUserId = currentUserId();

//     // Отримати повідомлення з бази даних для поточного користувача
//     $sql = "SELECT messages.*, users.name as sender_name FROM messages
//             INNER JOIN users ON messages.sender_id = users.id
//             WHERE messages.recipient_id = :loggedInUserId
//             ORDER BY messages.sent_at DESC";
//     $stmt = $conn->prepare($sql);
//     $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
//     $stmt->execute();

//     $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Вивести результат у форматі JSON
//     header('Content-Type: application/json');
//     echo json_encode($messages);

//     return $messages;
// } catch (PDOException $e) {
//     // Обробка помилок бази даних
//     echo json_encode(['error' => $e->getMessage()]);
// } finally {
//     if ($conn !== null) {
//         $conn = null;
//     }
// }



// function getMessagesByRecipient($recipientId)
// {
//     $conn = getPDO();

//     $stmt = $conn->prepare("SELECT * FROM messages WHERE recipient_id = ?");
//     $stmt->execute([$recipientId]);

//     $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     return $messages;
// }
