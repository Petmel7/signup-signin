<?php

// require_once '../actions/helpers.php';

// $imageFile = $_FILES['image'];
// $senderId = $_POST['sender_id'];
// $recipientId = $_POST['recipient_id'];

// $targetFile = uploadFile($imageFile, "image_url");

// if ($targetFile) {

//     $result = saveMessageWithImage($targetFile, $senderId, $recipientId);

//     header('Content-Type: application/json');
//     echo json_encode($result);
// } else {
//     echo json_encode(['error' => 'Invalid request']);
// }




require_once __DIR__ . '../../../vendor/autoload.php';
require_once __DIR__ . '../../actions/helpers.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imageFile = $_FILES['image'];
    $senderId = $_POST['sender_id'];
    $recipientId = $_POST['recipient_id'];

    $targetFile = uploadFile($imageFile, "image_url");

    if ($targetFile) {
        $result = saveMessageWithImage($targetFile, $senderId, $recipientId);
        echo json_encode(['image_url' => $targetFile, 'result' => $result]);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
