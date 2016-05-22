<?php

namespace App\Http\Services\OrderIdentity\Domain;

class OrderIdentity
{
    protected $order_id;
    protected $image;
    protected $signature;

    public function __construct($order_id, Image $image, SvgData $signature)
    {
        $this->order_id = $order_id;
        $this->image = $image;
        $this->signature = $signature;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getSignature()
    {
        return $this->signature;
    }
}
