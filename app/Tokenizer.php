<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tokenizer extends Model
{
    //
    protected $table = "tokenizer";

    protected $fillable = [
        'token', 'email', 'type'
    ];
}