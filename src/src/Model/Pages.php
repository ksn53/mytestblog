<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $table = "pages";
    protected $fillable = array("id","title", "content", "author");
    //public $timestamps = false;
}