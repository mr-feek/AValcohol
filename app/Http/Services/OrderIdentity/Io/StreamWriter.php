<?php

namespace App\Http\Services\OrderIdentity\Io;

class StreamWriter
{
    protected $directory;

    public function __construct($directory = '.') {
        $this->directory = $directory;
    }

    public function setDirectory($directory) {
        $this->directory = $directory;
    }

    private function getFilePointer($filename) {
        return fopen($this->directory.DIRECTORY_SEPARATOR.$filename, 'a');
    }

    public function write(Streamable $stream, $filename)
    {
        $fp = $this->getFilePointer($filename);
        if($fp == false) {
            return false;
        }
        while(!$stream->endOfStream()) {
            fwrite($fp, $stream->next());
        }
        return true;
    }
}
