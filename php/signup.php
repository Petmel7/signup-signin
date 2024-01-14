<?php

require_once __DIR__ . '/../hack/actions/helpers.php';

$uploadPath = '../../uploads';

// data
// $avatarPath = null;

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

if (!empty($avatar)) {
    $types = ['image/jpeg', 'image/png'];

    if (!in_array($avatar['type'], $types)) {
        addValidationError(fieldName: 'email', message: 'Не правельний формат картинки!');
    }

    if ($avatar['size'] / 1000000 > 1) {
        addValidationError(fieldName: 'email', message: 'Файл повинен бути менще одного мб!');
    }
}

if (!empty($avatar)) {
    $avatarPath = uploadFile($avatar, prefix: 'avatar');
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

// var_dump($avatarPath);


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
