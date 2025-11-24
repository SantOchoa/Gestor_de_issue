<?php
namespace App\Repositories;

use App\Controllers\TicketController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TicketsRepository{
    private $codesError = [
        1 => 403,
        'default' => 400
    ];
    public function createTicket(Request $request, Response $response){
        try{
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new TicketController();
            $ticket = $controller->createTiket(
                $data['title'],
                $data['description'],
                $data['status'],
                $data['userid'],
                $data['adminid']);
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
    public function queryallticket(Request $request,Response $response){
        try{
            $controller = new TicketController();
            $rows = $controller->queryallticket();
            $response->getBody()->write($rows);
            return $response->withHeader('Content-Type', 'application/json');

        }catch(Exception $ex){
            $status =  $this->codesError[$ex->getCode()] ?? $this->codesError['default'];
            return $response->withStatus($status);
        }
    }

}