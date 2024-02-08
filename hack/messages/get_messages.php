<?php
require_once '../actions/helpers.php';

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

        // SQL-запит для отримання повідомлень, адресованих конкретному користувачеві як відправник або отримувач
        $sql = "SELECT * FROM messages WHERE recipient_id = ? OR sender_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$recipientId, $recipientId]);

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
