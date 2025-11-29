<?php

use App\Repositories\AuthTokenRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Repositories\UserRepository;
$token= require __DIR__."/../Middleware/Token.php";



return function(App $app) use ($token){
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });
    $app->post('/login', [UserRepository::class, 'login']);

    $app->group('/view', function (RouteCollectorProxy $group) use ($token) {
        $group->get('/logout', [AuthTokenRepository::class, 'logout'])->add($token);
        $group->group('/admin', function (RouteCollectorProxy $group) use ($token){
            $group->get('/queryAllUsers',[UserRepository::class,'queryAllUsers'])->add($token);
            $group->get('/queryAllAdmin',[UserRepository::class,'queryAllAdmin'])->add($token);
            $group->post('/createuser',[UserRepository::class,'createuser'])->add($token);
        });
    });
};

