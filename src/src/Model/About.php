<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = "about";
    protected $fillable = array("id","text");
    public $timestamps = false;
}