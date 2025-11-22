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
            $token = AuthToken::insert(['user_id' =>$userid, 'token'=> $tokencod]);
        }else if(!empty($row)){
            $row->delete();
            $codeal= $this->generarCodigo();
            $tokencod= $tokenrole.'_'.$codeal;
            $token = AuthToken::insert(['user_id' =>$userid, 'token'=> $tokencod]);
        }
        return $tokencod;
    }
    public function getAuthToken(){
        $rows= AuthToken::all();
        if(count($rows)==0){
            return null;
        }
        return $rows->toArray();
    }
    function generarCodigo($length = 5) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return substr(str_shuffle($chars), 0, $length);
    }

}
