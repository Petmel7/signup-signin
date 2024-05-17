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

// // Emitted when a message is received from the client
// $ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers) {
//     $message = json_decode($data, true);

//     // Assuming each message contains sender_id, recipient_id, and message_text
//     if (isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
//         $senderId = $message['sender_id'];
//         $recipientId = $message['recipient_id'];
//         $messageText = $message['message_text'];

//         // Зберегти повідомлення в базу даних і перевірити, чи воно успішно збережено
//         if (saveMessage($senderId, $recipientId, $messageText)) {
//             // Повідомлення успішно збережено, надіслати його отримувачу
//             if (isset($connectedUsers[$recipientId])) {

//                 // Отримайте з'єднання отримувача і відправте йому повідомлення
//                 $recipientConnection = $connectedUsers[$recipientId];
//                 $recipientConnection->send(json_encode($message));
//             } else {
//                 // Обробте випадок, коли отримувач не підключений
//                 echo "Recipient with ID $recipientId is not connected\n";
//             }
//         } else {
//             // Обробити випадок, коли повідомлення не було збережено
//             // Наприклад, вивести повідомлення про помилку або відправити сповіщення про помилку
//             echo "Failed to save message\n";
//         }

//         // Assuming you want to send the message back to the sender for demonstration
//         $connection->send(json_encode(['echo_message' => $message]));
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

// Create a Websocket server
$ws_worker = new Worker('websocket://0.0.0.0:2346');

// Store connected users with their identifiers
$connectedUsers = [];

$ws_worker->onConnect = function ($connection) use (&$connectedUsers) {
    echo "Connection open\n";
};

$ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers) {
    $message = json_decode($data, true);

    if ($message !== null && isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
        $senderId = $message['sender_id'];
        $recipientId = $message['recipient_id'];
        $messageText = $message['message_text'];

        if (saveMessage($senderId, $recipientId, $messageText)) {
            $userId = $message['recipient_id'] ?? null;

            if ($userId !== null) {
                $connectedUsers = getUserById($userId);
                $connectedUsers[$userId] = $connection;
            }

            if (isset($connectedUsers[$recipientId])) {
                $recipientConnection = $connectedUsers[$recipientId];
                $recipientConnection->send(json_encode($message));
            } else {
                echo "Recipient with ID $recipientId is not connected\n";
            }
        } else {
            echo "Failed to save message\n";
        }

        $connection->send(json_encode(['echo_message' => $message]));
    } else {
        echo "Invalid message format or missing data\n";
    }
};

// Emitted when connection closed
$ws_worker->onClose = function ($connection) {
    echo "Connection closed\n";
};

// Run worker
Worker::runAll();



// require_once __DIR__ . '../../vendor/autoload.php';
// require_once __DIR__ . '../../hack/actions/helpers.php';

// use Workerman\Worker;

// // Create a Websocket server
// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// // Store connected users with their identifiers
// $connectedUsers = [];

// $ws_worker->onConnect = function ($connection) use (&$connectedUsers) {
//     echo "Connection open\n";

//     // Отримання ідентифікатора користувача з параметра запиту
//     $userId = $_GET['recipient_id'] ?? null;

//     // Перевірка, чи було передано значення ідентифікатора користувача
//     if ($userId !== null) {
//         // Додавання підключеного користувача до списку підключених за їхнім ідентифікатором
//         $connectedUsers[$userId] = $connection;
//     }
// };

// // Emitted when a message is received from the client
// $ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers) {
//     $message = json_decode($data, true);

//     // Assuming each message contains sender_id, recipient_id, and message_text
//     if (isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
//         $senderId = $message['sender_id'];
//         $recipientId = $message['recipient_id'];
//         $messageText = $message['message_text'];

//         // Зберегти повідомлення в базу даних і перевірити, чи воно успішно збережено
//         if (saveMessage($senderId, $recipientId, $messageText)) {
//             // Повідомлення успішно збережено, надіслати його отримувачу
//             if (isset($connectedUsers[$recipientId])) {

//                 // Отримайте з'єднання отримувача і відправте йому повідомлення
//                 $recipientConnection = $connectedUsers[$recipientId];
//                 $recipientConnection->send(json_encode($message));
//             } else {
//                 // Обробте випадок, коли отримувач не підключений
//                 echo "Recipient with ID $recipientId is not connected\n";
//             }
//         } else {
//             // Обробити випадок, коли повідомлення не було збережено
//             // Наприклад, вивести повідомлення про помилку або відправити сповіщення про помилку
//             echo "Failed to save message\n";
//         }

//         // Assuming you want to send the message back to the sender for demonstration
//         $connection->send(json_encode(['echo_message' => $message]));
//     }
// };

// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
// };

// // Run worker
// Worker::runAll();



// require_once __DIR__ . '../../vendor/autoload.php';
// require_once __DIR__ . '../../hack/actions/helpers.php';

// use Workerman\Worker;

// // Create a Websocket server
// $ws_worker = new Worker('websocket://0.0.0.0:2346');

// // Store connected users with their identifiers
// $connectedUsers = [];

// $ws_worker->onConnect = function ($connection) use (&$connectedUsers) {
//     echo "Connection open\n";

//     // Отримати значення параметра 'id' з JSON-повідомлення
//     $message = json_decode($connection->httpBuffer, true);
    
    // $userId = $message['recipient_id'] ?? null;

    // // Перевірити, чи було передано значення 'id'
    // if ($userId !== null) {
    //     // Додати підключеного користувача до списку підключених за їхнім ідентифікатором
    //     $connectedUsers[$userId] = $connection;
    // }
// };

// // Emitted when a message is received from the client
// $ws_worker->onMessage = function ($connection, $data) use (&$connectedUsers) {
//     $message = json_decode($data, true);

//     // Assuming each message contains sender_id, recipient_id, and message_text
//     if (isset($message['sender_id'], $message['recipient_id'], $message['message_text'])) {
//         $senderId = $message['sender_id'];
//         $recipientId = $message['recipient_id'];
//         $messageText = $message['message_text'];

//         // Зберегти повідомлення в базу даних і перевірити, чи воно успішно збережено
//         if (saveMessage($senderId, $recipientId, $messageText)) {
//             // Повідомлення успішно збережено, надіслати його отримувачу
//             if (isset($connectedUsers[$recipientId])) {

//                 // Отримайте з'єднання отримувача і відправте йому повідомлення
//                 $recipientConnection = $connectedUsers[$recipientId];
//                 $recipientConnection->send(json_encode($message));
//             } else {
//                 // Обробте випадок, коли отримувач не підключений
//                 echo "Recipient with ID $recipientId is not connected\n";
//             }
//         } else {
//             // Обробити випадок, коли повідомлення не було збережено
//             // Наприклад, вивести повідомлення про помилку або відправити сповіщення про помилку
//             echo "Failed to save message\n";
//         }

//         // Assuming you want to send the message back to the sender for demonstration
//         $connection->send(json_encode(['echo_message' => $message]));
//     }
// };

// // Emitted when connection closed
// $ws_worker->onClose = function ($connection) {
//     echo "Connection closed\n";
//     // При відключенні користувача видалити їх зі списку підключених користувачів
//     // Можна також додатково реалізувати логіку очищення списку підключених користувачів
//     global $connectedUsers;
//     foreach ($connectedUsers as $userId => $conn) {
//         if ($conn === $connection) {
//             unset($connectedUsers[$userId]);
//             break;
//         }
//     }
// };

// // Run worker
// Worker::runAll();




// $connectedUsers = [
//     $recipientId => $connection // де $connection - це з'єднання отримувача з ідентифікатором 31
// ];



// // Емітовано, коли новий користувач підключається
// $ws_worker->onConnect = function ($connection, $data) use (&$connectedUsers) {
//     $data = json_decode($data, true);

//     $userId = $data['recipient_id'] ?? null;
//     var_dump($data);

//     if ($userId !== null) {
//         $connectedUsers[$userId] = $connection;
//     }
// };


//++++++++++++++++++++++++++++++++++++++++++++++++++++
// require_once __DIR__ . '../../vendor/autoload.php';

// use Workerman\Worker;

// $worker = new Worker('websocket://0.0.0.0:2346');

// $worker->onMessage = function ($connection, $data) use ($worker) {
//     $id = $connection->id;
//     // Формування повідомлення у форматі JSON
//     $message = json_encode(["message" => "My connection ID is $id"]);
//     // Відправка JSON-повідомлення
//     var_dump($message);
//     $connection->send($message);
// };

// Worker::runAll();
//++++++++++++++++++++++++++++++++++++++++++++++++++++++
