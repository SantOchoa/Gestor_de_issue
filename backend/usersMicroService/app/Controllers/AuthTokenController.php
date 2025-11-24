<?php
namespace App\Controllers;

use App\Models\AuthToken;
use Exception;

class AuthTokenController{
    public function loginCreateToken($userid, $role){
        if($role=== 'gestor'){
            $positiontok = 'token_gestor_';
        }else if($role === 'admin'){
            $positiontok = 'token_admin_';
        }
        $tokenrole= $positiontok . $userid;
        $row = AuthToken::where('token', 'like', $tokenrole.'%')->first();
        if (empty($row)){
            $codeal= $this->generarCodigo();
            $tokencod= $tokenrole.'_'.$codeal;
            $m = new AuthToken();
            $m->user_id = $userid;
            $m->token= $tokencod;
            $m->save();
        }else if(!empty($row)){
            $row->delete();
            $codeal= $this->generarCodigo();
            $tokencod= $tokenrole.'_'.$codeal;
            $m = new AuthToken();
            $m->user_id = $userid;
            $m->token= $tokencod;
            $m->save();
        }
        return $tokencod;
    }
    public function logout($token){
        if (empty($token)){
            throw new Exception("No Token", 1);
        }
        $row = AuthToken::where('token', 'like', $token);
        $row->delete();
        return 'Sesion Closed';
    }
    function generarCodigo($length = 5) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return substr(str_shuffle($chars), 0, $length);
    }
    function verificateTokenDataBase($token){
        $row = AuthToken::where('token', 'like', $token)->first();
        if(empty($row)){
            return false;
        }else{
            return true;
        }
    }

}
