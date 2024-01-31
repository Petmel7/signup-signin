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
    } elseif ($_GET['page'] === 'my-friends-list') {
        // Обробка сторінки списку друзів
        require './hack/my-friends-list.php';
    } elseif ($_GET['page'] === 'my-subscribers-list') {
        // Обробка сторінки списку друзів
        require './hack/my-subscribers-list.php';
    } elseif ($_GET['page'] === 'his-friends-list') {
        // Обробка сторінки списку друзів
        require './hack/his-friends-list.php';
    } elseif ($_GET['page'] === 'his-subscribers-list') {
        // Обробка сторінки списку друзів
        require './hack/his-subscribers-list.php';
    } else {
        require './hack/signin-form.php';
    }
} else {
    require './hack/signin-form.php';
}

// his-subscribers-list
