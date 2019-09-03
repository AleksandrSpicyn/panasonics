<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    const LIKED_STATUS = 'liked';
    const UNLIKED_STATUS = 'unliked';

    protected $fillable = array('user_id', 'job_id', 'status');
    protected $visible = array('user_id', 'job_id', 'status');
    protected $table = 'job_likes';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
