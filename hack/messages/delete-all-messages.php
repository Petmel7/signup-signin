<?php
require_once '../actions/helpers.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentUserId = $data['sender_id'] ?? null;
    $otherUserId = $data['recipient_id'] ?? null;

    // var_dump("currentUserId", $currentUserId);
    // var_dump("otherUserId", $otherUserId);

    if ($currentUserId !== null && $otherUserId !== null) {
        try {
            $conn = getPDO();

            $sql = "DELETE FROM `messages` WHERE (sender_id = :current_user_id AND recipient_id = :other_user_id) OR (sender_id = :other_user_id AND recipient_id = :current_user_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':current_user_id', $currentUserId, PDO::PARAM_INT);
            $stmt->bindParam(':other_user_id', $otherUserId, PDO::PARAM_INT);
            $stmt->execute();

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        } finally {
            if ($conn !== null) {
                $conn = null;
            }
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
}








// $data = json_decode(file_get_contents("php://input"), true);

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $messageId = $data['message_id'] ?? null;
//     $currentUserId = $data['sender_id'] ?? null;
//     $recipientId = $data['recipient_id'] ?? null;

//     if ($messageId !== null && $currentUserId !== null && $recipientId !== null) {
//         try {
//             $conn = getPDO();

//             $sql = "DELETE FROM `messages` WHERE id = :message_id AND (sender_id = :current_user_id OR recipient_id = :current_user_id)";
//             $stmt = $conn->prepare($sql);
//             $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
//             $stmt->bindParam(':current_user_id', $currentUserId, PDO::PARAM_INT);
//             $stmt->execute();

//             header('Content-Type: application/json');
//             echo json_encode(['success' => true]);
//         } catch (PDOException $e) {
//             header('Content-Type: application/json');
//             echo json_encode(['error' => $e->getMessage()]);
//         } finally {
//             if ($conn !== null) {
//                 $conn = null;
//             }
//         }
//     } else {
//         header('Content-Type: application/json');
//         echo json_encode(['error' => 'Invalid request']);
//     }
// } else {
//     header('Content-Type: application/json');
//     echo json_encode(['error' => 'Invalid request method']);
// }
