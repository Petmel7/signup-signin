<?php

// require_once __DIR__ . '../../vendor/autoload.php';
// require_once __DIR__ . '../../hack/actions/helpers.php';

// use Workerman\Worker;

// // Create a Websocket server
// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// // Store connected users with their identifiers
// $connectedUsers = [];

// $ws_worker->onConnect = function ($connection) use (&$connectedUsers) {
//     echo "Connection open\n";
// };

// $ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers) {
//     $message = json_decode($data, true);

//     if ($message !== null && isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
//         $senderId = $message['sender_id'];
//         $recipientId = $message['recipient_id'];
//         $messageText = $message['message_text'];

//         if (saveMessage($senderId, $recipientId, $messageText)) {
//             $userId = $message['recipient_id'] ?? null;

//             if ($userId !== null) {
//                 $connectedUsers = getUserById($userId);
//                 $connectedUsers[$userId] = $connection;
//             }

//             if (isset($connectedUsers[$recipientId])) {
//                 $recipientConnection = $connectedUsers[$recipientId];
//                 $recipientConnection->send(json_encode($message));
//             } else {
//                 echo "Recipient with ID $recipientId is not connected\n";
//             }
//         } else {
//             echo "Failed to save message\n";
//         }

//         $connection->send(json_encode(['echo_message' => $message]));
//     } else {
//         echo "Invalid message format or missing data\n";
//     }
// };

// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
// };

// // Run worker
// Worker::runAll();




require_once __DIR__ . '../../vendor/autoload.php';
require_once __DIR__ . '../../hack/actions/helpers.php';

use Workerman\Worker;

$ws_worker = new Worker('websocket://0.0.0.0:2346');

$connectedUsers = [];

// Store connection ID to user ID mapping
$connectionUserMap = [];

$ws_worker->onConnect = function ($connection) {
    echo "Connection open\n";
    $connection->onWebSocketConnect = function ($connection) {
        global $connectedUsers, $connectionUserMap;

        // Assuming the client sends the user ID as a query parameter, for example: ws://localhost:2346/?user_id=1
        if (isset($_GET['id'])) {
            $userId = $_GET['id'];
            $connectedUsers[$userId] = $connection;
            $connectionUserMap[$connection->id] = $userId;
            echo "User $userId connected\n";
        } else {
            echo "No user ID provided\n";
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
                $recipientConnection->send(json_encode($message));
            } else {
                echo "Recipient with ID $recipientId is not connected\n";
            }
        } else {
            echo "Failed to save message\n";
        }
        var_dump($message);
        $connection->send(json_encode(['echo_message' => $message]));
    } else {
        echo "Invalid message format or missing data\n";
    }
};

$ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    if (isset($connectionUserMap[$connection->id])) {
        $userId = $connectionUserMap[$connection->id];
        unset($connectedUsers[$userId]);
        unset($connectionUserMap[$connection->id]);
        echo "Connection closed for user $userId\n";
    } else {
        echo "Connection closed\n";
    }
};

// Run worker
Worker::runAll();
