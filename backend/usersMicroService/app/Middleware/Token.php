<?php
namespace App\Middleware;

use App\Controllers\AuthTokenController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

return function (Request $request, RequestHandler $handler) {
    $headers = $request->getHeader('Authorization');
    $token = $headers[0] ?? null;

    $tokencontroller = new AuthTokenController();
    $tokenlistc = $tokencontroller->verificateTokenDataBase($token);

    if (!$tokenlistc) {
        $response = new Response();
        $response->getBody()->write(json_encode(['msg' => 'error']));
        return $response
            ->withStatus(401)
            ->withHeader('Content-Type', 'application/json');
    }

    return $handler->handle($request);
};