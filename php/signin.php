<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$success = false;

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    addOldValue('email', $email);
    addValidationError(fieldName: 'email', message: 'Неправильний формат пошти!');
    setMessage(key: 'error', message: 'Помилка валідації');
} else {
    $user = findUser($email);

    if (!$user) {
        setMessage(key: 'error', message: "Користувача $email не знайдено!");
    } elseif (!password_verify($password, $user['password'])) {
        setMessage(key: 'error', message: "Неправильний пароль!");
    } else {
        $_SESSION['user']['id'] = $user['id'];
        $_SESSION['user']['name'] = $user['name'];
        $success = true;
    }
}

echo json_encode(['success' => $success]);
