<?php

require_once __DIR__ . '../../vendor/autoload.php';
require_once __DIR__ . '../../hack/actions/helpers.php';

use Workerman\Worker;

$ws_worker = new Worker('websocket://0.0.0.0:2346');

$connectedUsers = [];
$connectionUserMap = [];

$ws_worker->onConnect = function ($connection) {
    echo "Connection open\n";
    $connection->onWebSocketConnect = function ($connection) {
        global $connectedUsers, $connectionUserMap;

        if (isset($_GET['sender_id']) && isset($_GET['recipient_id'])) {
            $senderId = $_GET['sender_id'];
            $recipientId = $_GET['recipient_id'];

            $connectionUserMap[$connection->id] = [$senderId, $recipientId];

            // Уникнення перевизначення з'єднання
            if (!isset($connectedUsers[$senderId])) {
                $connectedUsers[$senderId] = $connection;
                echo "User $senderId connected as sender\n";
            }
            if (!isset($connectedUsers[$recipientId])) {
                $connectedUsers[$recipientId] = $connection;
                echo "User $recipientId connected as recipient\n";
            }
        } else {
            echo "No sender or recipient ID provided\n";
        }
    };
};

$ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
    try {
        $message = json_decode($data, true);

        if (isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
            $senderId = $message['sender_id'];
            $recipientId = $message['recipient_id'];
            $messageText = $message['message_text'];
            saveMessage($senderId, $recipientId, $messageText);

            foreach ($connectedUsers as $userConnection) {
                $userConnection->send(json_encode(['messages' => [$message]]));
            }
        } else {
            $messages = getMessagesByRecipient($message['sender_id'], $message['recipient_id']);
            $users = getAllUsers();

            $responseData = [
                'messages' => $messages,
                'users' => $users
            ];

            $connection->send(json_encode(['success' => $responseData]));
        }
    } catch (Exception $e) {
        echo "Error processing message: " . $e->getMessage() . "\n";
    }
};

$ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    if (isset($connectionUserMap[$connection->id])) {
        list($senderId, $recipientId) = $connectionUserMap[$connection->id];

        // Видалення тільки відповідних з'єднань
        if (isset($connectedUsers[$senderId]) && $connectedUsers[$senderId] === $connection) {
            unset($connectedUsers[$senderId]);
        }
        if (isset($connectedUsers[$recipientId]) && $connectedUsers[$recipientId] === $connection) {
            unset($connectedUsers[$recipientId]);
        }

        unset($connectionUserMap[$connection->id]);

        echo "Connection closed for sender $senderId and recipient $recipientId\n";
    } else {
        echo "Connection closed\n";
    }
};

// Run worker
Worker::runAll();

// $ws_worker->connectionTimeout = 600; // Таймаут з'єднання у секундах (10 хвилин)
// $ws_worker->pingInterval = 30; // Інтервал пінгу у секундах
// $ws_worker->pingNotResponseLimit = 2; // Ліміт пінгів без відповіді
