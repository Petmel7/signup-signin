<?php

require_once __DIR__ . '/../hack/actions/helpers.php';

// data
$name = $_POST['name'];
$email = $_POST['email'];
$avatar = $_POST['avatar'];
$password = $_POST['password'];
$repeatPassword = $_POST['repeat-password'];

// validation
addOldValue('name', $name);
addOldValue('email', $email);

if (empty($name)) {

    addValidationError(fieldName: 'name', message: 'Неправельне імя!');
}

if (!filter_var($email, filter: FILTER_VALIDATE_EMAIL)) {

    addValidationError(fieldName: 'email', message: 'Неправельне пошта!');
}

if (!empty($password)) {
    addValidationError(fieldName: 'password', message: 'Пароль пустий!');
}

if (!empty($password === $repeatPassword)) {
    addValidationError(fieldName: 'password', message: 'Паролі не співпадають!');
}

if (!empty($_SESSION['validation'])) {
    $baseUrl = '/signup-signin';
    redirect($baseUrl . '/index.php?page=signup');
}

// add user db
