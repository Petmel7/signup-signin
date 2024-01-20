<?php

// require_once __DIR__ . '/helpers.php';

// $loggedInUsername = getLoggedInUsername();

// handleGetRequest($loggedInUsername);

// $baseUrl = '/signup-signin';
// redirect($baseUrl . '/index.php?page=friends-list');





require_once __DIR__ . '/helpers.php';

$loggedInUsername = getLoggedInUsername();

$friends = handleGetRequest($loggedInUsername);

header('Content-Type: application/json');
echo json_encode($friends);



var_dump($friends);
