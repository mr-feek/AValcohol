<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
	/**
	 * override base authorize in order to accept a collection passed as an argument
	 * @param string $ability
	 * @param mixed $arguments
	 * @return \Illuminate\Auth\Access\Response|void
	 */
    public function authorize($ability, $arguments = [])
    {
	    if ($arguments instanceof Collection) {
		    $collection = $arguments;

		    if ($collection->isEmpty()) {
			    return;
		    }

		    foreach ($collection as $model) {
			    // TODO: do we only need to check the first one?
			    parent::authorize($ability, $model);
		    }
		    return;
	    }

	    $this->authorize($ability, $arguments);
    }
}
