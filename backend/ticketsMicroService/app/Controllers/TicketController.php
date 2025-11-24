<?php
namespace App\Controllers;

use App\Models\Ticket;
use App\Controllers\AuthTokenController;
use App\Models\AuthToken;
use Exception;

class TicketController{
    public function createTiket($title,$description,$status,$userid,$adminid){
        $ticket = new Ticket();
        $ticket->titulo = $title;
        $ticket->descripcion = $description;
        $ticket->estado = $status;
        $ticket->gestor_id = $userid;
        $ticket->admin_id = $adminid;
        $ticket->save();
        return json_encode([
            $ticket
        ]);
        
    }
    public function queryallticket(){
        $rows = Ticket::all();
        return $rows->toJson();
    }
}