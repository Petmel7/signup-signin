<?php

require_once __DIR__ . '/helpers.php';

$loggedInUsername = getLoggedInUsername();
$friends = handleGetRequest($loggedInUsername);

header('Content-Type: application/json');
echo json_encode($friends);

// var_dump($friends);
