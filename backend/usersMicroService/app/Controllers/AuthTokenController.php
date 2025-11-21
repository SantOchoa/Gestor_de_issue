<?php
namespace App\Controllers;

use App\Models\AuthToken;
use Exception;

class AuthTokenController{

    public function login_create($userid, $position){
        if($position=== 'gestor'){
            $positiontok = 'token_gestor_1_abc';
        }else if($position === 'admin'){
            $positiontok = 'token_admin_1_xyz';
        }
        //Verificar que se realice bien la creacion de los tokens
        $row = AuthToken::where('token', 'like', $positiontok . '%')->first();
        $tokenlast = $row->latest();
        $tokenlast->split();
        $token = AuthToken::insert(['user_id' =>$userid, 'token'=> $positiontok. ($tokenlast+1)]);
        AuthToken::createToken($token);
    }
    public function getAuthToken(){
        $rows= AuthToken::all();
        if(count($rows)==0){
            return null;
        }
        return $rows->toArray();
    }
}
