<?php

if (isset($_GET['page'])) {
    if ($_GET['page'] === 'signup') {
        require './hack/signup-form.php';
    } elseif ($_GET['page'] === 'home') {
        require './hack/home.php';
    } else {
        require './hack/signin-form.php';
    }
} else {
    require './hack/signin-form.php';
}
