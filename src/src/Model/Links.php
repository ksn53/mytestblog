<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $table = "links";
    protected $fillable = array("id","title","url");
    public $timestamps = false;
}