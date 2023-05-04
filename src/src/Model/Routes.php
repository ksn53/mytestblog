<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    protected $table = "routes";
    protected $fillable = array("id","method", "route", "controller", "data");
    public $timestamps = false;
}