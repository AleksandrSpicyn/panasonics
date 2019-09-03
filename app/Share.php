<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = array('user_id', 'provider', 'job_id');
    protected $visible = array('user_id', 'provider', 'job_id');
    protected $table = 'job_shares';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
