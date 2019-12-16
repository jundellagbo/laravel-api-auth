<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Hasher;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        // email verification
        VerifyEmail::toMailUsing(function ($notifiable, $url){
            $mail = new MailMessage;
            $mail->subject('Email Verification');
            $mail->markdown('emails.verifyemail', ['verify' => Hasher::encode($notifiable->getKey()) . '/' . sha1($notifiable->getEmailForVerification())]);
            return $mail;
        });
    }
}
