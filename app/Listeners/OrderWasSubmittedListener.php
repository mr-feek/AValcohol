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
use Pusher;

class OrderWasSubmittedListener extends Listener implements ShouldQueue
{
	protected $pusher;

	public function __construct(Pusher $pusher)
	{
		$this->pusher = $pusher;
	}

	public function handle(OrderWasSubmitted $event) {
		// submit to pusher
		$order = $event->order; // THIS DOES NOT HAVE RELATIONSHIPS FETCHED
		$order->user = $order->user();

		$channel = $event->broadcastOn();
		$eventName = 'new_order';
		$message = $event->broadcastsWith();

		//$this->pusher->trigger($channel, $eventName, $message);
	}
}