<?php

namespace OrderIdentity\Model\Domain;

class FileStream implements Streamable
{
    protected $fp;
    private $nextChar = null;

    public function __construct($filename)
    {
        $this->fp = fopen($filename, 'r');
        $this->type = pathinfo($filename, PATHINFO_EXTENSION);
    }

    private function getNextChar() {
        $next = fgetc($this->fp);
        if($next == -1) {
            fclose($fp);
            return null;
        }
        return $next;
    }

    public function next()
    {
        return $this->getNextChar();
    }

    public function close()
    {
        return fclose($this->fp);
    }

    public function getType()
    {
        return $this->type;
    }

    public function typeAsExtension()
    {
        if(empty($this->type)) {
           return '';
        }
        return '.'.str_replace(' ', '', strtolower($this->type));
    }
}

?>
