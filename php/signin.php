<?php
require_once __DIR__ . '/../hack/actions/helpers.php';

$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if (empty($email) || !filter_var($email, filter: FILTER_VALIDATE_EMAIL)) {
    addOldValue('email', $email);
    addValidationError(fieldName: 'email', message: 'Не правельний формат пошти!');
    setMessage(key: 'error', message: 'Помилка валідації');

    $baseUrl = '/signup-signin';
    redirect($baseUrl . '/index.php?page=signin');
}

$user = findUser($email);

if (!$user) {
    setMessage(key: 'error', message: "Користувач $email не знайдений!");

    $baseUrl = '/signup-signin';
    redirect($baseUrl . '/index.php?page=signin');
}

if (!password_verify($password, $user['password'])) {
    setMessage(key: 'error', message: "Неправельний пароль!");

    $baseUrl = '/signup-signin';
    redirect($baseUrl . '/index.php?page=signin');
}

$_SESSION['user']['id'] = $user['id'];
$_SESSION['user']['name'] = $user['name'];

$baseUrl = '/signup-signin';
redirect($baseUrl . '/index.php?page=home');


// var_dump($_SESSION['user']['name']);
