<?php

namespace App;

use Illuminate\Support\Str;

use App\Tokenizer;

use Carbon\Carbon;

use Mail;

use App\Mail\ResetPassword;

use App\Mail\EmailVerification;

class UserToken
{
    public $args = null;
    
    public $passwordExpiry = 20;

    public function create($args) {
        $tokenizer = Tokenizer::firstOrNew([
            'email' => $args['email'],
            'type' => $args['type']
        ]);
        $tokenizer->token = str_replace('-', '', (string) Str::uuid());
        $tokenizer->save();
        $this->args = $tokenizer;
        return $this;
    }

    public function getVerifyToken($args) {
        $token = Tokenizer::where('token', $args)->where('type', 'verify')->first();
        if(!$token) return null;
        return $token;
    }

    public function getPasswordToken($args) {
        $token = Tokenizer::where('token', $args)->where('type', 'password_reset')->where('updated_at', '>', Carbon::now()->subMinutes($this->passwordExpiry))->first();
        if(!$token) {
            $this->delete($args);
            return null;
        }
        return $token;
    }

    public function delete($args) {
        Tokenizer::where('token', $args)->delete();
    }

    public function sendEmail() {
        $args = $this->args;
        if($args['type'] == 'verify') {
            return $this->sendMailVerify();
        }
        return $this->sendMailPasswordReset();
    }

    public function sendMailPasswordReset() {
        Mail::to([
            [
                'email' => $this->args['email'],
                'subject' => 'Reset Password'
            ]
        ])->send(new ResetPassword([ 'token' => $this->args['token'] ]));
        return 'sent';
    }

    public function sendMailVerify() {
        Mail::to([
            [
                'email' => $this->args['email'],
                'subject' => 'Email Verification'
            ]
        ])->send(new EmailVerification([ 'token' => $this->args['token'] ]));
        return 'sent';
    }
}