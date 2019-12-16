<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordTokenizer extends Model
{
    //
    protected $table = "password_tokenizer";

    protected $fillable = [
        'token', 'email'
    ];
}