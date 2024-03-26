<?php

require_once '../actions/helpers.php';

$imageFile = $_FILES['image'];
$senderId = $_POST['sender_id'];
$recipientId = $_POST['recipient_id'];

$targetFile = uploadFile($imageFile, "image_url");

if ($targetFile) {

    $result = saveMessageWithImage($targetFile, $senderId, $recipientId);

    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function saveMessageWithImage($targetFile, $senderId, $recipientId)
{
    try {
        $conn = getPDO();

        $sentAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO messages (sender_id, recipient_id, image_url, sent_at) 
        VALUES (:sender_id, :recipient_id, :image_url, :sent_at)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
        $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $targetFile, PDO::PARAM_STR);
        $stmt->bindParam(':sent_at', $sentAt, PDO::PARAM_STR);

        $stmt->execute();

        return ['success' => 'Message sent successfully'];
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}
