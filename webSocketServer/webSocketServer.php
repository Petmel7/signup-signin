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

            $connectedUsers[$connection->id] = $connection;
            $connectionUserMap[$connection->id] = [$senderId, $recipientId];

            echo "User $senderId connected as sender\n";
            echo "User $recipientId connected as recipient\n";

            // Відправка історії повідомлень
            $messages = getMessagesByRecipient($senderId, $recipientId);
            $users = getAllUsers();

            $responseData = [
                'messages' => $messages,
                'users' => $users
            ];

            $connection->send(json_encode(['success' => $responseData]));
        } else {
            echo "No sender or recipient ID provided\n";
        }
    };
};

$ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers, &$connectionUserMap) {
    $message = json_decode($data, true);

    if (isset($message['action']) && $message['action'] === 'delete') {
        if (isset($message['message_id'])) {
            $messageId = $message['message_id'];

            try {
                $conn = getPDO();

                $sql = "DELETE FROM `messages` WHERE id = :message_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
                $stmt->execute();

                foreach ($connectedUsers as $userConnection) {
                    list($senderId, $recipientId) = $connectionUserMap[$userConnection->id];
                    $messages = getMessagesByRecipient($senderId, $recipientId);
                    $users = getAllUsers();

                    $responseData = [
                        'messages' => $messages,
                        'users' => $users
                    ];

                    $userConnection->send(json_encode(['delete' => $responseData]));
                }
            } catch (PDOException $e) {
                echo "Error deleting message: " . $e->getMessage();
            } finally {
                if ($conn !== null) {
                    $conn = null;
                }
            }
        }
    } elseif (isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        saveMessage($senderId, $recipientId, $messageText);

        foreach ($connectedUsers as $userConnection) {
            $messages = getMessagesByRecipient($senderId, $recipientId);
            $users = getAllUsers();

            $responseData = [
                'messages' => $messages,
                'users' => $users
            ];

            $userConnection->send(json_encode(['success' => $responseData]));
        }
    }
};

$ws_worker->onClose = function ($connection) use (&$connectedUsers, &$connectionUserMap) {
    if (isset($connectionUserMap[$connection->id])) {
        list($senderId, $recipientId) = $connectionUserMap[$connection->id];

        unset($connectedUsers[$connection->id]);
        unset($connectionUserMap[$connection->id]);

        echo "Connection closed for sender $senderId and recipient $recipientId\n";
    } else {
        echo "Connection closed\n";
    }
};

Worker::runAll();
