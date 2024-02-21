<?php

require_once __DIR__ . '/helpers.php';

// $data = json_decode(file_get_contents('php://input'), true);

// if (isset($data['recipient_id'])) {
//     $currentUserId = $data['recipient_id'];

//     $unviewedMessagesCount = getUnviewedMessagesCount($currentUserId);

//     header('Content-Type: application/json');
//     echo json_encode(['unviewed_messages_count' => $unviewedMessagesCount]);
// } else {
//     echo json_encode(['error' => 'Invalid request']);
// }

// function getUnviewedMessagesCount($currentUserId)
// {
//     try {
//         $conn = getPDO();

//         $sql = "SELECT COUNT(*) AS unviewed_messages_count FROM messages WHERE recipient_id = :currentUserId AND viewed = FALSE";

//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':currentUserId', $currentUserId);
//         $stmt->execute();

//         $result = $stmt->fetch(PDO::FETCH_ASSOC);

//         return $result['unviewed_messages_count'];
//     } catch (PDOException $e) {
//         error_log($e->getMessage());
//         return 0;
//     }
// }



// Отримання кількості непереглянутих повідомлень та повідомлень для поточного користувача
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient_id'])) {
    $currentUserId = $data['recipient_id'];

    $unviewedMessages = getUnviewedMessagesForCurrentUser($currentUserId);
    $unviewedMessagesCount = count($unviewedMessages);

    header('Content-Type: application/json');
    echo json_encode(['unviewed_messages_count' => $unviewedMessagesCount, 'unviewed_messages' => $unviewedMessages]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function getUnviewedMessagesForCurrentUser($currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * FROM messages WHERE recipient_id = :currentUserId AND viewed = FALSE";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $messages;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}

function markMessageAsViewed($messageId, $currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "UPDATE messages SET viewed = TRUE WHERE id = :messageId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':messageId', $messageId);
        $stmt->execute();

        // Після відмітки повідомлення як переглянутого оновлюємо кількість непереглянутих повідомлень
        updateUnviewedMessagesCount($currentUserId); // Припустимо, що у вас є така функція
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

function updateUnviewedMessagesCount($currentUserId)
{
    try {
        $conn = getPDO();

        // $currentUserId = $_SESSION['user_id']; // Припустимо, що ви зберігаєте ідентифікатор поточного користувача у сесії

        $sql = "SELECT COUNT(*) AS unviewed_messages_count FROM messages WHERE recipient_id = :currentUserId AND viewed = FALSE";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['unviewed_messages_count'] = $result['unviewed_messages_count']; // Оновлюємо змінну у сесії, яка містить кількість непереглянутих повідомлень
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}






// var_dump($currentUserId);
