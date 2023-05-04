<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = "posts";
    protected $fillable = array("id","title", "content", "author", "teaserpic");
    //public $timestamps = false;

    public function comments()
    {
        return $this->hasMany('App\Model\Comments', 'postid')->where('parent', 0)->where('moderated', (int) 1);
    }
}

