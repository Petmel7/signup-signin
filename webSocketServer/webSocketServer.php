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

        unset($connectionUserMap[$connection->id]);

        echo "Connection closed for sender $senderId and recipient $recipientId\n";
    } else {
        echo "Connection closed\n";
    }
};

// Run worker
Worker::runAll();




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




// require_once __DIR__ . '../../vendor/autoload.php';
// require_once __DIR__ . '../../hack/actions/helpers.php';

// use Workerman\Worker;

// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// $connectedUsers = [];
// $connectionUserMap = [];

// $ws_worker->onConnect = function ($connection) {
//     echo "Connection open\n";
//     $connection->onWebSocketConnect = function ($connection) {
//         global $connectedUsers, $connectionUserMap;

//         if (isset($_GET['sender_id']) && isset($_GET['recipient_id'])) {
//             $senderId = $_GET['sender_id'];
//             $recipientId = $_GET['recipient_id'];

//             echo "Sender ID: $senderId, Recipient ID: $recipientId\n";

//             $connectedUsers[$senderId] = $connection;
//             $connectedUsers[$recipientId] = $connection;

//             $connectionUserMap[$connection->id] = [$senderId, $recipientId];

//             echo "User $senderId connected as sender\n";
//             echo "User $recipientId connected as recipient\n";

//             // Завантаження та відправка повідомлень при підключенні
//             sendInitialMessages($connection, $senderId, $recipientId);
//         } else {
//             echo "No sender or recipient ID provided\n";
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
//             $responseMessage = ['echo_message' => [$message]];

//             // Відправка повідомлення отримувачу
//             if (isset($connectedUsers[$recipientId])) {
//                 $recipientConnection = $connectedUsers[$recipientId];
//                 $recipientConnection->send(json_encode($responseMessage));
//                 echo "Message sent to recipient with ID $recipientId\n";
//             } else {
//                 echo "Recipient with ID $recipientId is not connected\n";
//             }

//             // Відправка повідомлення відправнику
//             if (isset($connectedUsers[$senderId])) {
//                 $senderConnection = $connectedUsers[$senderId];
//                 $senderConnection->send(json_encode($responseMessage));
//                 echo "Message sent to sender with ID $senderId\n";
//             } else {
//                 echo "Sender with ID $senderId is not connected\n";
//             }
//         } else {
//             echo "Failed to save message\n";
//         }
//     } else {
//         echo "Invalid message format or missing data\n";
//     }
// };

// $ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
//     if (isset($connectionUserMap[$connection->id])) {
//         list($senderId, $recipientId) = $connectionUserMap[$connection->id];

//         unset($connectionUserMap[$connection->id]);
//         unset($connectedUsers[$senderId]);
//         unset($connectedUsers[$recipientId]);

//         echo "Connection closed for sender $senderId and recipient $recipientId\n";
//     } else {
//         echo "Connection closed\n";
//     }
// };

// function sendInitialMessages($connection, $senderId, $recipientId)
// {
//     try {
//         $messages = getMessagesByRecipient($senderId, $recipientId);
//         $users = getAllUsers();

//         $responseData = [
//             'messages' => $messages,
//             'users' => $users
//         ];

//         $connection->send(json_encode(['success' => $responseData]));
//     } catch (Exception $e) {
//         $connection->send(json_encode(['error' => $e->getMessage()]));
//     }
// }

// function getAllUsers()
// {
//     $users = [];
//     try {
//         $conn = getPDO();
//         $sql = "SELECT * FROM users";
//         $stmt = $conn->prepare($sql);
//         $stmt->execute();
//         $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     } catch (PDOException $e) {
//         $users['error'] = 'Database error: ' . $e->getMessage();
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
//     return $users;
// }

// Worker::runAll();
