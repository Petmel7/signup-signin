<?php

require_once __DIR__ . '../../actions/helpers.php';

// if (isset($_SESSION['user']['id'])) {
//     if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["images"])) {
//         $imageUrl = $_FILES["images"];

//         $userId = $_SESSION['user']['id'];

//         $targetPath = uploadFile($imageUrl, "image_url");

//         $conn = getPDO();
//         $updateQuery = "UPDATE messages SET image_url = :image_url WHERE id = :userId";
//         $stmt = $conn->prepare($updateQuery);
//         $stmt->bindParam(':image_url', $targetPath);
//         $stmt->bindParam(':userId', $userId);

//         if ($stmt->execute()) {
//             echo json_encode(["success" => true, "message" => "Photo updated successfully."]);
//         } else {
//             echo json_encode(["success" => false, "message" => "Error updating photo"]);
//         }

//         $conn = null;
//         exit;
//     } else {
//         echo json_encode(["success" => false, "message" => "Images file not uploaded"]);
//     }
// } else {
//     echo json_encode(["success" => false, "message" => "User session not found"]);
// }




if (isset($_SESSION['sender_id'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["images"])) {
        $imageUrl = $_FILES["images"];

        $senderId = $_SESSION['sender_id'];

        $targetPath = uploadFile($imageUrl, "image_url");

        $conn = getPDO();
        $updateQuery = "UPDATE messages SET image_url = :image_url WHERE sender_id = :senderId";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':image_url', $targetPath);
        $stmt->bindParam(':senderId', $senderId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Photo updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating photo"]);
        }

        $conn = null;
        exit;
    } else {
        echo json_encode(["success" => false, "message" => "Images file not uploaded"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User session not found"]);
}
