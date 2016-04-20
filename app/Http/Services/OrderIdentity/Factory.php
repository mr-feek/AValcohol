<?php

namespace App\Http\Services\OrderIdentity;

class Factory
{
    public static function getDbMapper()
    {
        
    }

    public static function getStreamWriter()
    {
        return new StreamWriter();
    }
}
