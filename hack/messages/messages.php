<?php

require_once '../actions/helpers.php';

// Отримати дані з AJAX-запиту
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['sender_id'], $data['recipient_id'], $data['message_text'])) {
    $sender_id = $data['sender_id'];
    $recipient_id = $data['recipient_id'];
    $message_text = $data['message_text'];

    // Викликати функцію для збереження повідомлення в базі даних
    $result = saveMessage($sender_id, $recipient_id, $message_text);

    // Вивести результати у форматі JSON
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    // Обробка помилок, якщо не отримано всі необхідні дані з AJAX-запиту
    echo json_encode(['error' => 'Invalid request']);
}

function saveMessage($sender_id, $recipient_id, $message_text)
{
    try {
        $conn = getPDO();

        // Отримати поточний час для збереження в полі `sent_at`
        $sent_at = date('Y-m-d H:i:s');

        // SQL-запит для вставки нового повідомлення
        $sql = "INSERT INTO messages (sender_id, recipient_id, message_text, sent_at) 
                VALUES (:sender_id, :recipient_id, :message_text, :sent_at)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
        $stmt->bindParam(':recipient_id', $recipient_id, PDO::PARAM_INT);
        $stmt->bindParam(':message_text', $message_text, PDO::PARAM_STR);
        $stmt->bindParam(':sent_at', $sent_at, PDO::PARAM_STR);

        // Виконати SQL-запит
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
