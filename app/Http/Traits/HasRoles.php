<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/5/16
 * Time: 6:38 PM
 */

namespace App\Http\Traits;

trait HasRoles
{
	public function roles() {
		return $this->belongsToMany('App\Models\Role')->withTimestamps();
	}

	protected function assignRole(Role $role) {
		$this->roles()->attach($role);
	}

	protected function removeRole(Role $role) {
		$this->roles()->detach($role);
	}

	public function hasRole(string $name) {
		foreach ($this->roles as $role) {
			if ($role->name === $name) {
				return true;
			}
		}

		return false;
	}
}