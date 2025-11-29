<?php
namespace App\Repositories;

use App\Controllers\TicketsActividadController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TicketsActividadRepository{
    private $codesError = [
        1 => 403,
        'default' => 400
    ];
    public function createcommitticet(Request $request, Response $response){
        try{
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new TicketsActividadController();
            $ticket = $controller->createcommitticet(
                $data['ticket_id'],
                $data['user_id'],
                $data['mensaje']);
            $response
                ->withHeader('Content-Type', 'application/json')
                ->getBody()
                ->write($ticket);
            return $response;
        }catch(Exception $ex){
            $status =  $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }
}