<?php

// require_once '../actions/helpers.php';

// $data = json_decode(file_get_contents('php://input'), true);

// if (isset($data['sender_id'], $data['recipient_id'], $data['message_text'])) {
//     $senderId = $data['sender_id'];
//     $recipientId = $data['recipient_id'];
//     $messageText = $data['message_text'];

//     $result = saveMessage($senderId, $recipientId, $messageText);

//     header('Content-Type: application/json');
//     echo json_encode($result);
// } else {
//     echo json_encode(['error' => 'Invalid request']);
// }

// function saveMessage($senderId, $recipientId, $messageText)
// {
//     try {
//         $conn = getPDO();

//         $sent_at = date('Y-m-d H:i:s');

//         $sql = "INSERT INTO messages (sender_id, recipient_id, message_text, sent_at) 
//         VALUES (:sender_id, :recipient_id, :message_text, :sent_at)
//         ON DUPLICATE KEY UPDATE message_text = :message_text, sent_at = :sent_at";

//         $stmt = $conn->prepare($sql);

//         $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
//         $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
//         $stmt->bindParam(':message_text', $messageText, PDO::PARAM_STR);
//         $stmt->bindParam(':sent_at', $sent_at, PDO::PARAM_STR);

//         $stmt->execute();

//         return ['success' => 'Message sent successfully'];
//     } catch (PDOException $e) {
//         return ['error' => $e->getMessage()];
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }
