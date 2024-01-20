<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../../monolog-config.php';

$loggedInUsername = getLoggedInUsername();

handleGetRequest($loggedInUsername);

$baseUrl = '/signup-signin';
redirect($baseUrl . '/index.php?page=friends-list');

// var_dump($loggedInUsername);
