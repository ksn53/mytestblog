<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class Topmenu extends Model
{
    protected $table = "topmenu";
    protected $fillable = array("id","title","url");
    public $timestamps = false;
}