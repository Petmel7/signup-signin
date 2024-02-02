<?php
require_once '../actions/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримати дані з POST-запиту
    $commentId = $_POST['comment_id'] ?? null;

    if ($commentId !== null) {
        try {
            $conn = getPDO();

            // Викликати функцію для видалення коментаря з бази даних
            $sql = "DELETE FROM comments WHERE id = :comment_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
            $stmt->execute();

            // Повернути успішний результат
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Обробка помилок бази даних
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        } finally {
            if ($conn !== null) {
                $conn = null;
            }
        }
    } else {
        // Обробка помилок, якщо не отримано ідентифікатор коментаря
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    // Обробка помилок, якщо запит не є POST-запитом
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request method']);
}
