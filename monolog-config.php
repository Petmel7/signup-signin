<?php
require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('my_log');
$log->pushHandler(new StreamHandler('logs/my_log.log', Level::Warning));

// add records to the log
// $log->warning('Foo');
// $log->error('Bar');
