<?php

use Workerman\Worker;

require_once __DIR__ . '../../vendor/autoload.php';

// Include your database helpers file
require_once __DIR__ . '../../hack/actions/helpers.php';

// Create a Websocket server
$ws_worker = new Worker('websocket://0.0.0.0:2346');

// Emitted when new connection come
$ws_worker->onConnect = function ($connection) {
    echo "New connection\n";
};

// Emitted when data received
$ws_worker->onMessage = function ($connection, $data) {
    // Convert JSON data to array
    $message = json_decode($data, true);
    // var_dump($message);

    // Initialize arrays for messages and users
    $messages = [];
    $users = [];

    // Check if sender_id and recipient_id are set
    if (isset($message['sender_id'], $message['recipient_id'])) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];

        // Load messages from the database
        $messages = getMessagesByRecipient($senderId, $recipientId);
    } else {
        // Add error message to the response data array
        $messages['error'] = 'Invalid request';
    }

    // Load users from the database
    try {
        $conn = getPDO();

        $sql = "SELECT * FROM users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Add error message to the response data array
        $users['error'] = 'Database error: ' . $e->getMessage();
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }

    // Combine messages and users into one array
    $responseData = [
        'messages' => $messages,
        'users' => $users
    ];
    // Send the combined response data back to the client
    $connection->send(json_encode($responseData));
};

// Emitted when connection closed
$ws_worker->onClose = function ($connection) {
    echo "Connection closed\n";
};

// Run worker
Worker::runAll();
