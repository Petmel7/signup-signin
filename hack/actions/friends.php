<?php

require_once __DIR__ . '/helpers.php';

$loggedInUsername = getLoggedInUsername();
$friends = handleGetRequest($loggedInUsername);

header('Content-Type: application/json');
echo json_encode($friends);

// $baseUrl = '/signup-signin';
// redirect($baseUrl . '/index.php?page=friends-list');



// header('Content-Type: application/json');

// if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//     // Запит відбувається через AJAX, відправляємо JSON
//     echo json_encode($friends);
// } else {
//     // Запит не відбувається через AJAX, робимо редірект
//     $baseUrl = '/signup-signin';
//     redirect($baseUrl . '/index.php?page=friends-list');
// }






// var_dump($friends);
