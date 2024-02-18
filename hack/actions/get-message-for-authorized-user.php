<?php

require_once __DIR__ . '/helpers.php';

$currentUserId = currentUserId();
$messages = getMessageForAuthorizedUser($currentUserId);

header('Content-Type: application/json');
echo json_encode($messages);

function getMessageForAuthorizedUser($currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * FROM messages WHERE sender_id = :loggedInUsername"; // Використовуємо плейсхолдер :loggedInUsername

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId); // Передаємо значення через плейсхолдер
        $stmt->execute(); // Виконуємо запит без параметрів, оскільки вони вже прив'язані до плейсхолдера

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
