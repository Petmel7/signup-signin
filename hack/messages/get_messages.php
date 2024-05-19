<?php
require_once '../actions/helpers.php';

$message = json_decode(file_get_contents('php://input'), true);

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

header('Content-Type: application/json');
echo json_encode(['success' => $responseData]);
