<?php

use App\Controllers\TicketController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Repositories\TicketsRepository;

$token= require __DIR__."/../Middleware/Token.php";



return function(App $app) use ($token){
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });

    $app->group('/view', function (RouteCollectorProxy $group) use ($token) {
        $group->group('/user', function (RouteCollectorProxy $group) use ($token){
            $group->post('/createticket', [TicketsRepository::class, 'createTicket'])->add($token);
        });
    });
};

