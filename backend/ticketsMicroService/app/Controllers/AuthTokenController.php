<?php
namespace App\Controllers;

use App\Models\AuthToken;
use Exception;

class AuthTokenController{
    function verificateTokenDataBase($token){
        $row = AuthToken::where('token', 'like', $token)->first();
        if(empty($row)){
            return false;
        }else{
            return true;
        }
    }
}
