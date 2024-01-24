<?php
require_once '../actions/helpers.php';

// Отримати дані з AJAX-запиту
$data = json_decode(file_get_contents('php://input'), true);
var_dump($data);
if (isset($data['user_id'])) {
    $user_id = $data['user_id'];

    // Викликати функцію для отримання списку підписок користувача з бази даних
    $subscriptions = getSubscriptions($user_id);

    // Вивести результат в форматі JSON
    header('Content-Type: application/json');
    echo json_encode($subscriptions);
} else {
    // Обробка помилок, якщо не отримано всі необхідні дані з AJAX-запиту
    echo json_encode(['error' => 'Invalid request']);
}

// function getSubscriptions($user_id)
// {
//     try {
//         $conn = getPDO();

//         // Отримати список користувачів, на які підписаний конкретний користувач
//         $sql = "SELECT users.* FROM users
//                 INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
//                 WHERE subscriptions.subscriber_id = :user_id";
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
//         $stmt->execute();

//         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         return $results;
//     } catch (PDOException $e) {
//         // Обробка помилок бази даних
//         return ['error' => $e->getMessage()];
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }








// $data = $_POST;

// if (isset($data['subscriber_id']) && isset($data['target_user_id'])) {
//     $subscriber_id = $data['subscriber_id'];
//     $target_user_id = $data['target_user_id'];

//     // Викликати функцію для отримання списку підписок користувача з бази даних
//     $subscriptions = getSubscriptions($subscriber_id, $target_user_id);

//     // Вивести результат в форматі JSON
//     header('Content-Type: application/json');
//     echo json_encode($subscriptions);
// } else {
//     // Обробка помилок, якщо не отримано всі необхідні дані з POST-запиту
//     echo json_encode(['error' => 'Invalid request']);
// }

// function getSubscriptions($subscriber_id, $target_user_id)
// {
//     try {
//         $conn = getPDO();

//         // Отримати список користувачів, на які підписаний конкретний користувач
//         $sql = "SELECT users.* FROM users
//                 INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
//                 WHERE subscriptions.subscriber_id = :user_id";
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
//         $stmt->execute();

//         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         return $results;
//     } catch (PDOException $e) {
//         // Обробка помилок бази даних
//         return ['error' => $e->getMessage()];
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }










// $loggedInUserId = getLoggedInUserId();

// // Отримайте інформацію про підписників користувача
// $followers = getFollowers($loggedInUserId);

// header('Content-Type: application/json');
// echo json_encode($followers);

// function getFollowers($userId)
// {
//     try {
//         $conn = getPDO();

//         // SQL-запит для отримання інформації про підписників
//         $sql = "SELECT users.* FROM users
//                 INNER JOIN subscriptions ON users.id = subscriptions.subscriber_id
//                 WHERE subscriptions.target_user_id = :user_id";

//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
//         $stmt->execute();

//         $followers = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         return $followers;
//     } catch (PDOException $e) {
//         return ['error' => $e->getMessage()];
//     } finally {
//         if ($conn !== null) {
//             $conn = null;
//         }
//     }
// }
