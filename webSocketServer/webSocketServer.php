<?php
require_once __DIR__ . '../../vendor/autoload.php';
require_once __DIR__ . '../../hack/actions/helpers.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

// Make sure composer dependencies have been installed
// require __DIR__ . '/vendor/autoload.php';

/**
 * chat.php
 * Send any incoming messages to all connected clients (except sender)
 */
class MyChat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}

// Run the server application through the WebSocket protocol on port 8080
$app = new Ratchet\App('localhost', 8080);
$app->route('/chat', new MyChat, array('*'));
$app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
$app->run();


// use Ratchet\Server\IoServer;
// use Ratchet\Http\HttpServer;
// use Ratchet\WebSocket\WsServer;

// use Ratchet\MessageComponentInterface;
// use Ratchet\ConnectionInterface;

// class YourWebSocketHandler implements MessageComponentInterface
// {
//     protected $db;

//     public function __construct()
//     {
//         $this->db = getPDO();
//     }

//     public function onOpen(ConnectionInterface $conn)
//     {
//         // Логіка, яка виконується при відкритті з'єднання
//         $this->db->query("INSERT INTO connections (connection_id) VALUES ('{$conn->resourceId}')");
//     }

//     public function onMessage(ConnectionInterface $from, $msg)
//     {
//         // Логіка, яка виконується при отриманні повідомлення від клієнта
//         echo "Received message from {$from->resourceId}: $msg\n";
//     }

//     public function onClose(ConnectionInterface $conn)
//     {
//         // Логіка, яка виконується при закритті з'єднання
//         echo "Connection {$conn->resourceId} has disconnected\n";
//     }

//     public function onError(ConnectionInterface $conn, \Exception $e)
//     {
//         // Логіка, яка виконується при виникненні помилки
//         echo "An error has occurred: {$e->getMessage()}\n";
//         $conn->close();
//     }
// }

// $server = IoServer::factory(
//     new HttpServer(
//         new WsServer(
//             new YourWebSocketHandler()
//         )
//     ),
//     9090 // Порт, на якому запускається сервер
// );

// $server->run();
