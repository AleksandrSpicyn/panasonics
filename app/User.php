<?php

namespace App;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use EntrustUserTrait;
    const GENDER_MEN = 'male';
    const GENDER_WOMEN = 'female';
    const GENDERS = array(
        self::GENDER_MEN,
        self::GENDER_WOMEN
    );
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'first_name',
        'second_name',
        'email',
        'gender',
        'birth_date',
        'password',
        'mindbox_id',
        'newsletter',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $visible = [
        'id',
        'first_name',
        'second_name',
        'email',
        'gender',
        'birth_date',
        'password',
        'mindbox_id',
        'newsletter',
    ];

    public function jobs()
    {
        return $this->hasOne(Job::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id', 'id');
    }

    public function shares()
    {
        return $this->hasMany(Share::class, 'user_id', 'id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
