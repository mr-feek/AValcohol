<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo '<pre>';

$app = require __DIR__.'/../bootstrap/app.php';


use App\Models\DeliveryZone;
use App\Models\DeliveryZone\Point;

$zone = DeliveryZone::create([
        'name' => 'NewZone', 
        'points' => [new Point(0,0), new Point(0,4), new Point(4,4), new Point(4, 0)]
]);

$zones = DeliveryZone::containsPoint(new Point(1, 1));
$notInZone = DeliveryZone::containsPoint(new Point(4,5));

echo 'In Zone'.PHP_EOL;
var_dump($zones);

echo 'Not In Zone'.PHP_EOL;
var_dump($notInZone);
