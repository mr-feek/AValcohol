<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 1:02 AM
 */

namespace App\Models\Repositories\Interfaces;

interface UserAddressInterface {
	public function create($data);
	public function get($id);
	public function update($id);
	public function canDeliverToAddress($data);
}