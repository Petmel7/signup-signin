<?php

require_once __DIR__ . '/../hack/actions/helpers.php';

$uploadPath = '../../uploads';

// data

$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$avatar = $_FILES['avatar'] ?? null;
$password = $_POST['password'] ?? null;
$repeatPassword = $_POST['repeat-password'] ?? null;

// validation

if (empty($name)) {

    addValidationError(fieldName: 'name', message: 'Неправельне імя!');
}

if (!filter_var($email, filter: FILTER_VALIDATE_EMAIL)) {

    addValidationError(fieldName: 'email', message: 'Неправельне пошта!');
}

$avatar = $_FILES['avatar'] ?? null;

if (!empty($avatar) && $avatar['size'] > 0) {
    $types = ['image/jpeg', 'image/png'];

    if (!in_array($avatar['type'], $types)) {
        addValidationError(fieldName: 'avatar', message: 'Неправильний формат картинки!');
    }

    if ($avatar['size'] / 1000000 > 1) {
        addValidationError(fieldName: 'avatar', message: 'Файл повинен бути менше одного мб!');
    }

    if (!hasValidationError(fieldName: 'avatar')) {
        $avatarPath = uploadFile($avatar, prefix: 'avatar');
    }
} else {

    $avatarPath = 'uploads/avatar_4367658739.jpg';
}

if (empty($password)) {
    addValidationError(fieldName: 'password', message: 'Пароль пустий!');
}

if (empty($password === $repeatPassword)) {
    addValidationError(fieldName: 'password', message: 'Паролі не співпадають!');
}

if (!empty($_SESSION['validation'])) {
    addOldValue('name', $name);
    addOldValue('email', $email);

    $baseUrl = '/signup-signin';
    redirect($baseUrl . '/index.php?page=signup');
}

$pdo = getPDO();

// add user db

$query = "INSERT INTO users (name, email, avatar, password) VALUES (:name, :email, :avatar, :password)";

$params = [
    'name' => $name,
    'email' => $email,
    'avatar' => $avatarPath,
    'password' => password_hash($password, PASSWORD_DEFAULT)
];

$stmt = $pdo->prepare($query);

try {
    $stmt->execute($params);
} catch (\Exception $e) {
    die('Conection error: ' . $e->getMessage());
}

if (empty($_SESSION['validation'])) {
    $baseUrl = '/signup-signin';
    redirect($baseUrl . '/index.php?page=signin');
}

// var_dump($avatarPath);