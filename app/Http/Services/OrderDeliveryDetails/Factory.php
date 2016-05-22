<?php

namespace App\Http\Services\OrderIdentity;

class Factory
{
    public static function getDbWriter()
    {
        
    }

    public static function getStreamWriter()
    {
        return new StreamWriter();
    }
}
