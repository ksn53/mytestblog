<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $table = "user_role";
    protected $fillable = array("user_id","role_id");
    public $timestamps = false;
}