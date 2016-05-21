<?php

namespace App\Http\Services\OrderIdentity\Domain;

use Streamable;

class Base64Stream implements Streamable
{
    protected $data;
    protected $pointer = 0;
    protected $nextChar;

    public function __construct($base64data)
    {
        $this->data = str_split(base64_decode($base64data));
    }

    public function next()
    {
        if($this->nextChar != null) {
            return $this->nextChar;
        }
        if(!array_key_exists($this->pointer, $this->data)) {
            return null;
        }
        $next = $this->data[$this->pointer];
        $this->pointer += 1;
        return $next;
    }
}
