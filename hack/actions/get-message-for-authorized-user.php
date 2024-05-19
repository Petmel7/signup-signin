<?php

require_once __DIR__ . '/helpers.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['recipient_id'])) {
    $currentUserId = $data['recipient_id'];

    $messageAuthors = getMessageAuthorsForCurrentUser($currentUserId);

    header('Content-Type: application/json');
    echo json_encode(['message_authors' => $messageAuthors]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function getMessageAuthorsForCurrentUser($currentUserId)
{
    try {
        $conn = getPDO();

        $sql = "SELECT * 
        FROM messages
        WHERE recipient_id = :currentUserId";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentUserId', $currentUserId);
        $stmt->execute();

        $messageAuthors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $messageAuthors;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
}



// $data = json_decode(file_get_contents('php://input'), true);

// // Initialize arrays for messages and users
// $messages = [];
// $users = [];

// // Check if sender_id and recipient_id are set
// if (isset($data['recipient_id'])) {

//     $currentUserId = $data['recipient_id'];

//     // Load messages from the database
//     $messages = getMessageAuthorsForCurrentUser($currentUserId);
// } else {
//     // Add error message to the response data array
//     $messages['error'] = 'Invalid request';
// }

// function getMessageAuthorsForCurrentUser($currentUserId)
// {
//     try {
//         $conn = getPDO();

//         $sql = "SELECT * 
//         FROM messages
//         WHERE recipient_id = :currentUserId";

//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':currentUserId', $currentUserId);
//         $stmt->execute();

//         $messageAuthors = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         return $messageAuthors;
//     } catch (PDOException $e) {
//         error_log($e->getMessage());
//         return [];
//     }
// }

// // Load users from the database
// try {
//     $conn = getPDO();

//     $sql = "SELECT * FROM users";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();

//     $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     // Add error message to the response data array
//     $users['error'] = 'Database error: ' . $e->getMessage();
// } finally {
//     if ($conn !== null) {
//         $conn = null;
//     }
// }

// // Combine messages and users into one array
// $responseData = [
//     'messages' => $messages,
//     'users' => $users
// ];

// header('Content-Type: application/json');
// echo json_encode(['success' => $responseData]);
