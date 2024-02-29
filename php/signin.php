<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$success = false;

if (isset($email) && isset($password)) {
    $user = findUser($email);

    if ($user) {
        // Ви перевірили, що користувач з такою поштою існує
        if (password_verify($password, $user['password'])) {
            // Перевірка вірності пароля
            $_SESSION['user']['id'] = $user['id'];
            $_SESSION['user']['name'] = $user['name'];
            $success = true;
        } else {
            // Неправильний пароль
            // Можливо, ви також хочете встановити повідомлення про помилку
        }
    } else {
        // Користувача з такою поштою не знайдено
        // Можливо, ви також хочете встановити повідомлення про помилку
    }
}

echo json_encode(['success' => $success]);
