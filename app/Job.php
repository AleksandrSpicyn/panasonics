<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    const JOB_ACCEPT_STATUS = 'accepted';
    const JOB_WAIT_STATUS = 'waited';
    const JOB_REFUSE_STATUS = 'refused';
    const JOB_DELETE_STATUS = 'deleted';
    const STATUSES = array(
        self::JOB_ACCEPT_STATUS,
        self::JOB_WAIT_STATUS,
        self::JOB_REFUSE_STATUS,
        self::JOB_DELETE_STATUS
    );
    public $fillable = array(
        'id',
        'title',
        'description',
        'status',
        'comment',
        'image',
        'user_id',
        'created_at'
    );
    public $visible = array(
        'id',
        'title',
        'description',
        'status',
        'comment',
        'image',
        'user_id',
        'user',
        'created_at'
    );
    public $dates = array(
        'updated_at',
        'created_at'
    );

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'job_id', 'id');
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'job_id', 'id');
    }
}
