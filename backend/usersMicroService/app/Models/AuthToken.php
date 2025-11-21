<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    protected $table = "auth_tokens";
    public $timestamps = false;
}