<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Config/database.php';

$endpoints = require __DIR__ . '/../app/Endpoints/endpoints.php';
$cors = require __DIR__.'/../app/Middleware/Cors.php';
$app = AppFactory::create();


$endpoints($app);
$cors($app);


$app->run();