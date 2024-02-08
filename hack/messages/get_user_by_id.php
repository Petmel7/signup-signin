<?php

require_once '../actions/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $senderId = $data['id']; // Використовуємо $senderId замість $recipientId
    $success = getLoggedInUser($senderId); // Передаємо $senderId

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
