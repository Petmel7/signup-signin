<?php

use Workerman\Worker;

require_once __DIR__ . '../../vendor/autoload.php';

require_once __DIR__ . '../../hack/actions/helpers.php';

// Create a Websocket server
$ws_worker = new Worker('websocket://0.0.0.0:2346');

// Збереження підключених користувачів з їхніми ідентифікаторами
$connectedUsers = [];

// // Емітовано, коли новий користувач підключається
// $ws_worker->onConnect = function ($connection, $data) use (&$connectedUsers) {
//     echo "New connection\n";
//     $message = json_decode($data, true);
//     if (isset($message['sender_id'], $message['recipient_id'])) {
//         $senderId = $message['sender_id'];
//         $recipientId = $message['recipient_id'];
//         // Отримати ID підключеного користувача з даних підключення
//         $userId = getMessagesByRecipient($senderId, $recipientId);
//         var_dump($userId);
//         // Додати підключеного користувача до списку підключених
//         $connectedUsers[$userId] = $connection;
//     }
// };

// Емітовано, коли новий користувач підключається
$ws_worker->onConnect = function ($connection) {
    echo "New connection\n";
};

// Емітовано, коли отримано повідомлення
$ws_worker->onMessage = function ($connection, $data) use ($connectedUsers) {
    $message = json_decode($data, true);

    // Перевірте, чи є призначення та відправте повідомлення лише конкретному користувачеві
    if (isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        // Зберегти повідомлення в базу даних і перевірити, чи воно успішно збережено
        if (saveMessage($senderId, $recipientId, $messageText)) {
            // Повідомлення успішно збережено, надіслати його отримувачу
            if (isset($connectedUsers[$recipientId])) {
                // Отримайте з'єднання отримувача і відправте йому повідомлення
                $recipientConnection = $connectedUsers[$recipientId];
                $recipientConnection->send(json_encode($message));
            } else {
                // Обробте випадок, коли отримувач не підключений
                echo "Recipient with ID $recipientId is not connected\n";
            }
        } else {
            // Обробити випадок, коли повідомлення не було збережено
            // Наприклад, вивести повідомлення про помилку або відправити сповіщення про помилку
            echo "Failed to save message\n";
        }
    }
};

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

// // Emitted when data received
// $ws_worker->onMessage = function ($connection, $data) {
//     // Convert JSON data to array
//     $message = json_decode($data, true);
//     // var_dump($message);

//     // Initialize arrays for messages and users
//     $messages = [];
//     $users = [];

//     // Check if sender_id and recipient_id are set
//     if (isset($message['sender_id'], $message['recipient_id'])) {
//         $senderId = $message['sender_id'];
//         $recipientId = $message['recipient_id'];

//         // Load messages from the database
//         $messages = getMessagesByRecipient($senderId, $recipientId);
//     } else {
//         // Add error message to the response data array
//         $messages['error'] = 'Invalid request';
//     }

//     // Load users from the database
//     try {
//         $conn = getPDO();

//         $sql = "SELECT * FROM users";
//         $stmt = $conn->prepare($sql);
//         $stmt->execute();

//         $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     } catch (PDOException $e) {
//         // Add error message to the response data array
//         $users['error'] = 'Database error: ' . $e->getMessage();
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }

//     // Combine messages and users into one array
//     $responseData = [
//         'messages' => $messages,
//         'users' => $users
//     ];
//     // Send the combined response data back to the client
//     $connection->send(json_encode($responseData));
// };

// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
// };

// // Run worker
// Worker::runAll();








// use Workerman\Worker;

// require_once __DIR__ . '../../vendor/autoload.php';

// require_once __DIR__ . '../../hack/actions/helpers.php';

// // Create a Websocket server
// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// // Збереження підключених користувачів з їхніми ідентифікаторами
// $connectedUsers = [];

// // Емітовано, коли новий користувач підключається
// $ws_worker->onConnect = function ($connection) {
//     // Автентифікуйте користувача та отримайте його ідентифікатор
//     $userId = authenticateUser($connection);

//     // Збережіть з'єднання користувача разом з його ідентифікатором
//     $connectedUsers[$userId] = $connection;
// };

// // Емітовано, коли отримано повідомлення
// $ws_worker->onMessage = function ($connection, $data) use ($connectedUsers) {
//     $message = json_decode($data, true);

//     // Перевірте, чи є призначення та відправте повідомлення лише конкретному користувачеві
//     if (isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
//         $recipientId = $message['recipient_id'];

//         // Перевірте, чи існує підключений користувач з вказаним ідентифікатором
//         if (isset($connectedUsers[$recipientId])) {
//             // Отримайте з'єднання отримувача і відправте йому повідомлення
//             $recipientConnection = $connectedUsers[$recipientId];
//             $recipientConnection->send(json_encode($message));
//         } else {
//             // Обробте випадок, коли отримувач не підключений
//             echo "Recipient with ID $recipientId is not connected\n";
//         }
//     }
// };

// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
// };

// // Run worker
// Worker::runAll();
