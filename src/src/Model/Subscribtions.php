<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class Subscribtions extends Model
{
    protected $table = "subscribtions";
    protected $fillable = array("id","email");
    //public $timestamps = false;
}