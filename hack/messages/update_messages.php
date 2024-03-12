<?php
require_once '../actions/helpers.php';

// Отримуємо дані з тіла запиту
$data = json_decode(file_get_contents("php://input"), true);

// Перевіряємо, чи метод запиту POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримуємо ID повідомлення з даних запиту
    $messageId = $data['message_id'] ?? null;

    // Перевіряємо, чи ID повідомлення не пустий
    if ($messageId !== null) {
        try {
            // Отримуємо з'єднання з базою даних
            $conn = getPDO();

            // Підготовлюємо та виконуємо SQL запит для оновлення повідомлення
            $sql = "UPDATE `messages` SET message_text = :message_text WHERE id = :message_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':message_text', $data['message_text'], PDO::PARAM_STR);
            $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->execute();

            // Повертаємо успішний результат
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Обробляємо випадок помилки
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        } finally {
            // Закриваємо з'єднання з базою даних
            if ($conn !== null) {
                $conn = null;
            }
        }
    } else {
        // Повертаємо помилку у випадку, якщо ID повідомлення відсутнє
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    // Повертаємо помилку у випадку, якщо метод запиту не POST
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
}
