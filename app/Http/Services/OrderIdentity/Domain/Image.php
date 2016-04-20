<?php

namespace App\Http\Services\OrderIdentity\Domain;

class Image
{
    protected $data;
    protected $extension;

    public function __construct(Streamable $data, $extension = '.image')
    {
        $this->data = $data;
        $this->extension = $extension;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getExtension()
    {
        return $this->extension;
    }
}
