<?php
namespace App\Controllers;

use App\Models\User;
use App\Controllers\AuthTokenController;
use Exception;

class UserController
{

    public function login($email, $password)
    {
        $row = User::where('email', $email)
            ->where('password', $password)
            ->first();
        if (empty($row)) {
            throw new Exception("User null", 1);
        }
        $userid= $row->id;
        $userrole = $row->role;
        $auth= new AuthTokenController();
        $authtoken= $auth->loginCreateToken($userid,$userrole);
        return json_encode([
            $row,
            $authtoken,
        ]);
    }

    public function getUsers(){
        $rows = User::all();
        if(count($rows)==0){
            return null;
        }
        return $rows->toJson();
    }

}