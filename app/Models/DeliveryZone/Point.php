<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\DeliveryZone;

class Point
{   
    public function __construct($lat = null, $long = null)
    {
        $this->latitude = (double) $lat;
        $this->longitude = (double) $long;
    }
    
    public function __toString()
    {
        return $this->latitude.' '.$this->longitude;
    }
}
