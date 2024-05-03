<?php

// use Workerman\Worker;

// require_once __DIR__ . '../../vendor/autoload.php';

// // Include your database helpers file
// require_once __DIR__ . '../../hack/actions/helpers.php';

// // Create a Websocket server
// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// // Emitted when new connection come
// $ws_worker->onConnect = function ($connection) {
//     echo "New connection\n";
// };

// // Emitted when data received
// $ws_worker->onMessage = function ($connection, $data) {
//     // Here you can process the data, for example, store it in the database
//     // Convert JSON data to array
//     $message = json_decode($data, true);

//     // Save the message to the database
//     $result = saveMessage($message['sender_id'], $message['recipient_id'], $message['message_text']);

//     // Send response back to the client
//     $connection->send(json_encode($result));

//     // Get messages by recipient
//     $messages = getMessagesByRecipient($message['sender_id'], $message['recipient_id']);

//     // Send messages back to the client
//     $connection->send(json_encode($messages));

//     // Get user by ID
//     $users = getUserById($message);

//     // Send users back to the client
//     $connection->send(json_encode($users));
// };

// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
// };

// // Run worker
// Worker::runAll();


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
    // Here you can process the data, for example, store it in the database
    // Convert JSON data to array
    $message = json_decode($data, true);

    // Save the message to the database
    $result = saveMessage($message['sender_id'], $message['recipient_id'], $message['message_text']);

    // Send response back to the client
    $connection->send(json_encode($result));
};

// Emitted when connection closed
$ws_worker->onClose = function ($connection) {
    echo "Connection closed\n";
};

// Run worker
Worker::runAll();
