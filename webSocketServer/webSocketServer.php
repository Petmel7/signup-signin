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

            $connectedUsers[$senderId] = $connection;
            $connectedUsers[$recipientId] = $connection;

            $connectionUserMap[$connection->id] = [$senderId, $recipientId];

            // var_dump($connectionUserMap[$connection->id] = [$senderId, $recipientId, $messageText]);

            echo "User $senderId connected as sender\n";
            echo "User $recipientId connected as recipient\n";
        } else {
            echo "No sender or recipient ID provided\n";
        }
    };
};

$ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
    $message = json_decode($data, true);

    if ($message !== null && isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        if (saveMessage($senderId, $recipientId, $messageText)) {
            if (isset($connectedUsers[$recipientId])) {
                $recipientConnection = $connectedUsers[$recipientId];
                $responseMessage = [
                    'echo_message' => [$message]
                ];
                $recipientConnection->send(json_encode($responseMessage));
                echo "Message sent to recipient with ID $recipientId\n";
            } else {
                echo "Recipient with ID $recipientId is not connected\n";
            }
        } else {
            echo "Failed to save message\n";
        }
        // var_dump($message);
        $connection->send(json_encode(['echo_message' => [$message]]));
    } else {
        echo "Invalid message format or missing data\n";
    }
};

$ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    if (isset($connectionUserMap[$connection->id])) {
        list($senderId, $recipientId) = $connectionUserMap[$connection->id];

        unset($connectedUsers[$senderId]);
        unset($connectedUsers[$recipientId]);

        unset($connectionUserMap[$connection->id]);

        echo "Connection closed for sender $senderId and recipient $recipientId\n";
    } else {
        echo "Connection closed\n";
    }
};

// Run worker
Worker::runAll();










// require_once __DIR__ . '../../vendor/autoload.php';
// require_once __DIR__ . '../../hack/actions/helpers.php';
// require_once __DIR__ . '/ws_handlers.php';

// use Workerman\Worker;

// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// $connectedUsers = [];
// $connectionUserMap = [];

// $ws_worker->onConnect = function ($connection) {
//     onConnectHandler($connection);
// };

// $ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
//     onMessageHandler($connection, $data, $connectedUsers, $connectionUserMap);
// };

// $ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
//     onCloseHandler($connection, $connectedUsers, $connectionUserMap);
// };

// Worker::runAll();






// require_once __DIR__ . '../../vendor/autoload.php';
// require_once __DIR__ . '../../hack/actions/helpers.php';
// require_once __DIR__ . '/ws_handlers.php';

// use Workerman\Worker;

// // Створення першого WebSocket-сервера на порту 2346
// $ws_worker_1 = new Worker('websocket://0.0.0.0:2346');

// $connectedUsers = [];
// $connectionUserMap = [];

// // Обробники для першого сервера
// $ws_worker_1->onConnect = function ($connection) {
//     onConnectHandler($connection);
// };

// $ws_worker_1->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
//     onMessageHandler($connection, $data, $connectedUsers, $connectionUserMap);
// };

// $ws_worker_1->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
//     onCloseHandler($connection, $connectedUsers, $connectionUserMap);
// };

// Worker::runAll();
