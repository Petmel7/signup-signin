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

$ws_worker->onMessage = function ($connection, $data) {
    // Here you can process the data, for example, store it in the database
    // Convert JSON data to array
    $message = json_decode($data, true);

    // Save the message to the database
    $result = saveMessage($message['sender_id'], $message['recipient_id'], $message['message_text']);

    // Send response back to the client
    $connection->send(json_encode($result));

    // Send the message to both sender and recipient if they are connected
    sendToConnectedUsers($message);
};

function sendToConnectedUsers($message)
{
    global $ws_worker;

    foreach ($ws_worker->connections as $connection) {
        // Check if the connection's userId matches the recipient_id
        if ($connection->userId == $message['recipient_id']) {
            $connection->send(json_encode($message));
            break; // Stop looping once the message is sent to the recipient
        }
    }
}

// Emitted when connection closed
$ws_worker->onClose = function ($connection) {
    echo "Connection closed\n";
};

// Run worker
Worker::runAll();


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

// // // Emitted when data received
// // $ws_worker->onMessage = function ($connection, $data) {
// //     // Here you can process the data, for example, store it in the database
// //     // Convert JSON data to array
// //     $message = json_decode($data, true);

// //     // Save the message to the database
// //     $result = saveMessage($message['sender_id'], $message['recipient_id'], $message['message_text']);

// //     // Send response back to the client
// //     $connection->send(json_encode($result));
// // };

// $ws_worker->onMessage = function ($connection, $data) {
//     // Here you can process the data, for example, store it in the database
//     // Convert JSON data to array
//     $message = json_decode($data, true);

//     // Save the message to the database
//     $result = saveMessage($message['sender_id'], $message['recipient_id'], $message['message_text']);

//     // Send response back to the client
//     $connection->send(json_encode($result));

//     function getUserConnection($userId)
//     {
//         global $ws_worker;

//         foreach ($ws_worker->connections as $connection) {
//             // Перевіряємо, чи збігається ідентифікатор користувача
//             if ($connection->userId === $userId) {
//                 return $connection;
//             }
//         }

//         return null;
//     }


//     // Send the message to both sender and recipient
//     $senderConnection = getUserConnection($message['sender_id']);
//     $recipientConnection = getUserConnection($message['recipient_id']);

//     if ($senderConnection) {
//         $senderConnection->send(json_encode($message));
//     }

//     if ($recipientConnection) {
//         $recipientConnection->send(json_encode($message));
//     }
// };


// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
// };

// // Run worker
// Worker::runAll();






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
// };

// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
// };

// // Run worker
// Worker::runAll();



// use Workerman\Worker;

// require_once __DIR__ . '../../vendor/autoload.php';

// // Create a Websocket server
// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// // Emitted when new connection come
// $ws_worker->onConnect = function ($connection) {
//     echo "New connection\n";
// };

// // Emitted when data received
// $ws_worker->onMessage = function ($connection, $data) {
//     // Send hello $data
//     $connection->send('Hello ' . $data);
// };

// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
// };

// // Run worker
// Worker::runAll();
