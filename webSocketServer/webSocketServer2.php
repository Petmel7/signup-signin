<?php
require_once __DIR__ . '../../vendor/autoload.php';
require_once __DIR__ . '../../hack/actions/helpers.php';
require_once __DIR__ . '/ws_handlers.php';

use Workerman\Worker;

// Створення другого WebSocket-сервера на порту 2347
$ws_worker_2 = new Worker('websocket://0.0.0.0:2347');

$connectedUsers = [];
$connectionUserMap = [];

// Обробники для другого сервера
$ws_worker_2->onConnect = function ($connection) {
    // onConnectHandler($connection);
    $connection->onWebSocketConnect = function ($connection) {
        global $connectedUsers, $connectionUserMap;

        if (isset($_GET['sender_id']) && isset($_GET['recipient_id'])) {
            $senderId = $_GET['sender_id'];
            $recipientId = $_GET['recipient_id'];

            echo "Sender ID: $senderId, Recipient ID: $recipientId\n";

            $connectedUsers[$senderId] = $connection;
            $connectedUsers[$recipientId] = $connection;

            $connectionUserMap[$connection->id] = [$senderId, $recipientId];

            echo "User $senderId connected as sender\n";
            echo "User $recipientId connected as recipient\n";

            // Завантаження та відправка повідомлень при підключенні
            sendInitialMessages($connection, $senderId, $recipientId);
        } else {
            echo "No sender or recipient ID provided\n";
        }
    };
};

$ws_worker_2->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
    // onMessageHandler($connection, $data, $connectedUsers, $connectionUserMap);

    $message = json_decode($data, true);
    // var_dump($message);
    if (
        $message !== null && isset($message['sender_id'], $message['recipient_id'], $message['message_text'])
    ) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        sendInitialMessages($connection, $senderId, $recipientId);
    }
};

$ws_worker_2->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    onCloseHandler($connection, $connectedUsers, $connectionUserMap);
};

function sendInitialMessages($connection, $senderId, $recipientId)
{
    try {
        $messages = getMessagesByRecipient($senderId, $recipientId);
        $users = getAllUsers();

        $responseData = [
            'messages' => $messages,
            'users' => $users
        ];

        $connection->send(json_encode(['success' => $responseData]));
    } catch (Exception $e) {
        $connection->send(json_encode(['error' => $e->getMessage()]));
    }
}

Worker::runAll();
