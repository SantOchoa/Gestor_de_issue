<?php
namespace App\Controllers;

use App\Models\Ticket;
use App\Controllers\AuthTokenController;
use App\Models\AuthToken;
use Exception;

class TicketController{
    public function createTiket($title,$description,$status,$userid){
        $ticket = new Ticket();
        $ticket->titulo = $title;
        $ticket->descripcion = $description;
        $ticket->estado = $status;
        $ticket->gestor_id = $userid;
        
        $ticket->save();
        return json_encode([
            $ticket
        ]);
        
    }
    public function queryallticket(){
        $rows = Ticket::all();
        return $rows->toJson();
    }
    public function queryticketbyid($gestor_id){
        $rows = Ticket::where('gestor_id',$gestor_id)->get();
        return $rows->toJson();
    }
}