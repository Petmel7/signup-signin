<?php

require_once '../actions/helpers.php';

// $data = json_decode(file_get_contents('php://input'), true);

// if (isset($data['sender_id'], $data['recipient_id'], $data['image_url'])) {
//     $senderId = $data['sender_id'];
//     $recipientId = $data['recipient_id'];
//     $imageUrl = $_FILES["image"]; // Отримання файлу зображення

//     // Перевірка значень після отримання
//     var_dump($senderId);
//     var_dump($recipientId);
//     var_dump($imageUrl);

//     $result = saveMessageWithImage($senderId, $recipientId, $imageUrl);

//     // Перевірка результату виконання функції
//     var_dump($result);

//     header('Content-Type: application/json');
//     echo json_encode($result);
// } else {
//     echo json_encode(['error' => 'Invalid request']);
// }


// function saveMessageWithImage($senderId, $recipientId, $imageUrl)
// {
//     try {
//         $conn = getPDO();

//         $targetPath = uploadFile($imageUrl, "image_url");

//         // Підготовка даних для запиту
//         $sentAt = date('Y-m-d H:i:s');

//         // SQL-запит для вставки повідомлення з зображенням
//         $sql = "INSERT INTO messages (sender_id, recipient_id, image_url, sent_at) 
//         VALUES (:sender_id, :recipient_id, :image_url, :sent_at)";

//         $stmt = $conn->prepare($sql);

//         // Параметри запиту
//         $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
//         $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
//         $stmt->bindParam(':image_url', $targetPath, PDO::PARAM_STR);
//         $stmt->bindParam(':sent_at', $sentAt, PDO::PARAM_STR);

//         // Виконання запиту
//         $stmt->execute();

//         // Повернення результату успіху
//         return ['success' => 'Message sent successfully'];
//     } catch (PDOException $e) {
//         // Повернення помилки, якщо її спостережено
//         return ['error' => $e->getMessage()];
//     } finally {
//         // Закриття з'єднання з базою даних
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }





// $data = json_decode(file_get_contents('php://input'), true);

// if (isset($data['sender_id'], $data['recipient_id'], $data['image_url'])) {
//     $senderId = $data['sender_id'];
//     $recipientId = $data['recipient_id'];

//     // Перевірка, чи був успішно завантажений файл
//     if (isset($_FILES['image'])) {
//         $imageUrl = $_FILES["image"]; // Отримання файлу зображення
//     } else {
//         echo json_encode(['error' => 'Image file not uploaded']);
//         exit;
//     }

//     // Перевірка значень після отримання
//     var_dump($senderId);
//     var_dump($recipientId);
//     var_dump($imageUrl);

//     $result = saveMessageWithImage($senderId, $recipientId, $imageUrl);

//     // Перевірка результату виконання функції
//     var_dump($result);

//     header('Content-Type: application/json');
//     echo json_encode($result);
// } else {
//     echo json_encode(['error' => 'Invalid request']);
// }

// function saveMessageWithImage($senderId, $recipientId, $imageUrl)
// {
//     try {
//         $conn = getPDO();

//         $targetPath = uploadFile($imageUrl, "image_url");

//         // Підготовка даних для запиту
//         $sentAt = date('Y-m-d H:i:s');

//         // SQL-запит для вставки повідомлення з зображенням
//         $sql = "INSERT INTO messages (sender_id, recipient_id, image_url, sent_at) 
//         VALUES (:sender_id, :recipient_id, :image_url, :sent_at)";

//         $stmt = $conn->prepare($sql);

//         // Параметри запиту
//         $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
//         $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
//         $stmt->bindParam(':image_url', $targetPath, PDO::PARAM_STR);
//         $stmt->bindParam(':sent_at', $sentAt, PDO::PARAM_STR);

//         // Виконання запиту
//         $stmt->execute();

//         // Повернення результату успіху
//         return ['success' => 'Message sent successfully'];
//     } catch (PDOException $e) {
//         // Повернення помилки, якщо її спостережено
//         return ['error' => $e->getMessage()];
//     } finally {
//         // Закриття з'єднання з базою даних
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }





$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['sender_id'], $data['recipient_id'])) {
    $senderId = $data['sender_id'];
    $recipientId = $data['recipient_id'];

    // Перевірка, чи був успішно завантажений файл
    if (isset($_FILES['image'])) {
        $imageUrl = $_FILES["image"]; // Отримання файлу зображення
    } else {
        echo json_encode(['error' => 'Image file not uploaded']);
        exit;
    }

    // Перевірка значень після отримання
    var_dump($senderId);
    var_dump($recipientId);
    var_dump($imageUrl);

    $result = saveMessageWithImage($senderId, $recipientId, $imageUrl);

    // Перевірка результату виконання функції
    var_dump($result);

    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function saveMessageWithImage($senderId, $recipientId, $imageUrl)
{
    try {
        $conn = getPDO();

        $targetPath = uploadFile($imageUrl, "image_url");

        // Підготовка даних для запиту
        $sentAt = date('Y-m-d H:i:s');

        // SQL-запит для вставки повідомлення з зображенням
        $sql = "INSERT INTO messages (sender_id, recipient_id, image_url, sent_at) 
        VALUES (:sender_id, :recipient_id, :image_url, :sent_at)";

        $stmt = $conn->prepare($sql);

        // Параметри запиту
        $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
        $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $targetPath, PDO::PARAM_STR);
        $stmt->bindParam(':sent_at', $sentAt, PDO::PARAM_STR);

        // Виконання запиту
        $stmt->execute();

        // Повернення результату успіху
        return ['success' => 'Message sent successfully'];
    } catch (PDOException $e) {
        // Повернення помилки, якщо її спостережено
        return ['error' => $e->getMessage()];
    } finally {
        // Закриття з'єднання з базою даних
        if ($conn !== null) {
            $conn = null;
        }
    }
}
