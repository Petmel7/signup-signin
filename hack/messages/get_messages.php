<?php
require_once '../actions/helpers.php';

try {
    $conn = getPDO();

    // Отримати повідомлення з бази даних
    $sql = "SELECT messages.*, users.name as sender_name FROM messages
            INNER JOIN users ON messages.sender_id = users.id
            ORDER BY messages.sent_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Вивести результат у форматі JSON
    header('Content-Type: application/json');
    echo json_encode($messages);
} catch (PDOException $e) {
    // Обробка помилок бази даних
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if ($conn !== null) {
        $conn = null;
    }
}
