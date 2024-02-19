<?php

require_once __DIR__ . '/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['sender_id'])) {
    $currentUserId = $data['sender_id'];

    $success = getMessageForAuthorizedUser($currentUserId);

    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function getMessageForAuthorizedUser($currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * FROM messages WHERE sender_id = :currentUserId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $messages;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}

// var_dump($currentUserId);
