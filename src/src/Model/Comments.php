<?php
namespace App\Model;

use \Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = "comments";
    protected $fillable = array("id","title", "content", "author", "updated_at", "created_at", "parent", "postid");

    public function subcomments()
    {
        return $this->hasMany('App\Model\Comments', 'parent');
    }

    public function post()
    {
        return $this->belongsTo('App\Model\Posts', 'postid');
    }
    public function moderated()
    {
        return $this->where('moderated', (int) 1);
    }
}