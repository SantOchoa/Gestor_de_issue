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
            "user" => $row,
            "token" => $authtoken
        ]);
    }

    public function getUsers(){
        $rows = User::where('role','gestor')->get();
        if(empty($rows)){
           throw new Exception("User null", 1);
        }
        return $rows->toJson();
    }
    public function getAdmins(){
        $rows = User::where('role','admin')->get();
        if(empty($rows)){
           throw new Exception("User null", 1);
        }
        return $rows->toJson();
    }
    public function createuser($name,$email,$password,$role){
        $user= new User();
        $user->name=$name;
        $user->email = $email;
        $user->password = $password;
        $user->role = $role;
        $user->save();
        return json_encode([
            $user
        ]);
    }

}