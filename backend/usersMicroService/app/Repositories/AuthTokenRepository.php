<?php
namespace App\Repositories;

use App\Controllers\AuthTokenController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthTokenRepository{
    private $codesError = [
        1 => 403,
        'default' => 400
    ];
    public function logout(Request $request, Response $response){
        try{
            $headers = $request->getHeader('Authorization');
            $token = $headers[0] ?? null;
            $authcontroller = new AuthTokenController();
            $auth= $authcontroller->logout($token);
             $response
                ->withHeader('Content-Type', 'application/json')
                ->getBody()
                ->write($auth);
            return $response;
        }catch(Exception $ex){
            $status =  $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }

    }




}