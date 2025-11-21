<?php

use App\Controllers\AuthTokenController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Psr7\Response;

return function (App $app) {
    $app->add(function (Request $request, $handler) {
        $headers = $request->getHeader('Authorization');
        $token = $headers[0] ?? null;
        // Ejemplo simple: validar un token fijo
        $tokencontroller = new AuthTokenController();
        $tokenlistc= $tokencontroller->getAuthToken();
        foreach($tokenlistc as $token1 ){
            if ($token !==  $token1/*Aqui va con un for*/) {
            $response = new Response();
            $response
                ->getBody()
                ->write(json_encode(['msg' => 'error']));
            return $response
                ->withStatus(401)
                ->withHeader('Content-Type', 'application/json');
            }
        }
        
        // Si pasa, continúa al siguiente middleware o a la ruta
        return $handler->handle($request);
    });
};