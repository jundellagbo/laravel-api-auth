<?php

namespace App;

use Hashids;

class Hasher
{
    public static function encode($args)
    {
        return Hashids::encode($args);
    }
    public static function decode($enc)
    {
        if (is_int($enc)) {
            return $enc;
        }
        return count(Hashids::decode($enc)) ? Hashids::decode($enc)[0] : null;
    }
}