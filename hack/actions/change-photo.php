<?php

require_once __DIR__ . '/helpers.php';

if (isset($_SESSION['user']['id'])) {

    $conn = getPDO();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
        $avatar = $_FILES["avatar"];

        $userId = $_SESSION['user']['id'];

        $targetPath = uploadFile($avatar, "avatar");

        $updateQuery = "UPDATE users SET avatar = '$targetPath' WHERE id = $userId";

        if ($conn->query($updateQuery) === TRUE) {
            echo "Photo updated successfully.";
        } else {
            echo
            "Error updating photo " . implode(", ", $conn->errorInfo());
        }
    }

    $conn = null;
} else {
    echo "User session not found.";
}

$baseUrl = '/signup-signin';
redirect($baseUrl . '/index.php?page=home');
