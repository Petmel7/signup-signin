1 Винести пошук друзів на другу сторінку
2 Зробити пошук

При натисканні на цю кнопку <form id="friendsForm" action="hack/actions/friends.php" method="post">
<button class="friends" type="submit">Search friends</button>

</form> йде перенаправа до hack/actions/friends.php а там вже знаходиться $baseUrl = '/signup-signin';
redirect($baseUrl . '/index.php?page=friends-list'); який перенаправить на friends-list.php його ще треба сюди добавити

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
    } else {
        require './hack/signin-form.php';
    }
} else {
    require './hack/signin-form.php';
}

 де при натисканні на цю кнопку

<form id="friendsForm" action="hack/actions/friends.php" method="post">
            <button class="friends" type="submit" onclick="displayFriends(event)">Search friends</button>
        </form> 
        яка знаходиться в home.php реренаправити мене на friends-list.php
