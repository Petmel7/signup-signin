<?php

if (isset($_GET['page'])) {
    if ($_GET['page'] === 'signup') {
        require './hack/signup-form.php';
    } elseif ($_GET['page'] === 'home') {
        require './hack/home.php';
    } elseif ($_GET['page'] === 'user' && isset($_GET['username'])) {
        // Обробка сторінки користувача
        $username = $_GET['username'];
        // Використовуйте $username для отримання даних про користувача та відображення сторінки
        require './hack/user-page.php';
    } elseif ($_GET['page'] === 'friends-list') {
        // Обробка сторінки списку друзів
        require './hack/friends-list.php';
    } else {
        require './hack/signin-form.php';
    }
} else {
    require './hack/signin-form.php';
}
