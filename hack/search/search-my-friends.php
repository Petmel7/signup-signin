<?php
require_once '../actions/helpers.php';

// Отримати дані з AJAX-запиту
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($_SESSION['user']['name'])) {
    $name = $data['name'];
    $loggedInUsername = $_SESSION['user']['name'];

    // Викликати функцію для пошуку в базі даних
    $results = searchMyFriendsByName($name, $loggedInUsername);

    // Вивести результати у форматі JSON
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    // Обробка помилок, якщо не отримано ім'я з AJAX-запиту або користувач не авторизований
    echo json_encode(['error' => 'Invalid request']);
}

function searchMyFriendsByName($name, $loggedInUsername)
{
    try {
        $conn = getPDO();

        $sql = "SELECT users.name, users.avatar FROM users
        INNER JOIN subscriptions ON users.id = subscriptions.target_user_id
        WHERE users.name LIKE :search AND users.name <> :loggedInUsername
        AND subscriptions.subscriber_id = :loggedInUserId";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', "%{$name}%", PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUsername', $loggedInUsername, PDO::PARAM_STR);
        $stmt->bindValue(':loggedInUserId', $_SESSION['user']['id'], PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (PDOException $e) {

        return ['error' => $e->getMessage()];
    } finally {
        if ($conn !== null) {
            $conn = null;
        }
    }
}
