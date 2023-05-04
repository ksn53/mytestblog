<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";
    protected $fillable = ["id", "name", "password", "email", "subscribed", "userpic", "about"];
    public $timestamps = false;
}