<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Mail\ResetPassword;
use App\Mail\EmailVerification;
use App\Hasher;
use Mail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName', 'lastName', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['xuuid'];

    public function getXuuidAttribute() {
        return Hasher::encode($this->id);
    }

    // reset password notification
    public function sendPasswordResetNotification($token) {
        Mail::to([
            [
                'email' => $this->email,
                'subject' => 'Reset Password'
            ]
        ])->send(new ResetPassword([ 'token' => $token ]));
    }
}