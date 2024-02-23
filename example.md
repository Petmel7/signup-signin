Потрібно створити щоб на акаунт авторизованого користувача приходили повідомленя і відображались цифрами біля кнопки по якій при кліку перенаправляло до авторів коментарів

Потрібно написати код php який буде робити запит в базу даних і буде повертати всі повідомлення які написані авторизованому користувачеві

Потрібно створити сторінку де будуть відобрахатися користувачі які написпли повідомлення

Реалізував відображеня в цифрах скільки повідомлень прийшло і перехід на сторінку хто написав коментарі


<?php if (hasMessage(key: 'error')) : ?>

<?php if (hasMessage('error')) : ?>



$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient_id'])) {
    $currentUserId = $data['recipient_id'];

    $unviewedMessages = getUnviewedMessagesForCurrentUser($currentUserId);
    $unviewedMessagesCount = count($unviewedMessages);

    // Позначаємо отримані повідомлення як переглянуті
    foreach ($unviewedMessages as $message) {
        markMessageAsViewed($message['id'], $currentUserId);
    }

    // Оновлюємо кількість непереглянутих повідомлень у сесії
    updateUnviewedMessagesCount($currentUserId);

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

        updateUnviewedMessagesCount($currentUserId);
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

function updateUnviewedMessagesCount($currentUserId)
{
    try {
        $conn = getPDO();

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