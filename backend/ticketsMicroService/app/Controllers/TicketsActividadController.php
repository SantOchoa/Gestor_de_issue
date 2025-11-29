<?php
namespace App\Controllers;

use App\Models\TicketActividad;
use App\Controllers\AuthTokenController;
use App\Models\AuthToken;
use Exception;

class TicketsActividadController{
    public function createcommitticet($ticket_id,$user_id,$mensaje){
        $ticket = new TicketActividad();
        $ticket->ticket_id = $ticket_id;
        $ticket->user_id = $user_id;
        $ticket->mensaje = $mensaje;
        $ticket->save();
        return json_encode([
            $ticket
        ]);
    }
    
}

