<?php

// require_once __DIR__ . '../../vendor/autoload.php';
// require_once __DIR__ . '../../hack/actions/helpers.php';

// use Workerman\Worker;

// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// $connectedUsers = [];

// // Store connection ID to user ID mapping
// $connectionUserMap = [];

// $ws_worker->onConnect = function ($connection) {
//     echo "Connection open\n";
//     $connection->onWebSocketConnect = function ($connection) {
//         global $connectedUsers, $connectionUserMap;

//         // Assuming the client sends the user ID as a query parameter, for example: ws://localhost:2346/?user_id=1
//         if (isset($_GET['id'])) {
//             $userId = $_GET['id'];
//             $connectedUsers[$userId] = $connection;
//             $connectionUserMap[$connection->id] = $userId;
//             echo "User $userId connected\n";
//         } else {
//             echo "No user ID provided\n";
//         }
//     };
// };

// $ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
//     $message = json_decode($data, true);

//     if ($message !== null && isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
//         $senderId = $message['sender_id'];
//         $recipientId = $message['recipient_id'];
//         $messageText = $message['message_text'];

//         if (saveMessage($senderId, $recipientId, $messageText)) {
//             if (isset($connectedUsers[$recipientId])) {
//                 $recipientConnection = $connectedUsers[$recipientId];
//                 $responseMessage = [
//                     'echo_message' => [$message]
//                 ];
//                 $recipientConnection->send(json_encode($responseMessage));
//             } else {
//                 echo "Recipient with ID $recipientId is not connected\n";
//             }
//         } else {
//             echo "Failed to save message\n";
//         }
//         var_dump($message);
//         $connection->send(json_encode(['echo_message' => [$message]]));
//     } else {
//         echo "Invalid message format or missing data\n";
//     }
// };

// $ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
//     if (isset($connectionUserMap[$connection->id])) {
//         $userId = $connectionUserMap[$connection->id];
//         unset($connectedUsers[$userId]);
//         unset($connectionUserMap[$connection->id]);
//         echo "Connection closed for user $userId\n";
//     } else {
//         echo "Connection closed\n";
//     }
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

        // Перевіряємо, чи є в запиті параметри sender_id та recipient_id
        if (isset($_GET['sender_id']) && isset($_GET['recipient_id'])) {
            $senderId = $_GET['sender_id'];
            $recipientId = $_GET['recipient_id'];

            // Зберігаємо ідентифікатори з'єднання та користувача
            $connectedUsers[$senderId] = $connection;
            $connectedUsers[$recipientId] = $connection;

            // Мапуємо з'єднання до їх ідентифікаторів
            $connectionUserMap[$connection->id] = [$senderId, $recipientId];

            // Виводимо повідомлення про підключення користувачів
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
        var_dump($message);
        $connection->send(json_encode(['echo_message' => [$message]]));
    } else {
        echo "Invalid message format or missing data\n";
    }
};

$ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    if (isset($connectionUserMap[$connection->id])) {
        list($senderId, $recipientId) = $connectionUserMap[$connection->id];

        // Позначаємо з'єднання як закрите, але не видаляємо його зі списку підключених користувачів
        unset($connectionUserMap[$connection->id]);

        echo "Connection closed for sender $senderId and recipient $recipientId\n";
    } else {
        echo "Connection closed\n";
    }
};

// Run worker
Worker::runAll();





// $ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
//     if (isset($connectionUserMap[$connection->id])) {
//         list($senderId, $recipientId) = $connectionUserMap[$connection->id];

//         unset($connectedUsers[$senderId]);
//         unset($connectedUsers[$recipientId]);

//         unset($connectionUserMap[$connection->id]);

//         echo "Connection closed for sender $senderId and recipient $recipientId\n";
//     } else {
//         echo "Connection closed\n";
//     }
// };
