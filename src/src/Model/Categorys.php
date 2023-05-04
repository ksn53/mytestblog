<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class Categorys extends Model
{
    protected $table = "categorys";
    protected $fillable = array("id","name");
    public $timestamps = false;
}