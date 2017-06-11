<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 12/19/15
 * Time: 6:06 PM
 */

namespace App\Listeners;


use App\Events\OrderWasSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderWasSubmittedListener extends Listener implements ShouldQueue
{
	public function handle(OrderWasSubmitted $event) {
	}
}