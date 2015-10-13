<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 10/8/15
 * Time: 1:55 PM
 */

namespace Models;

use Model;

abstract class BaseModel extends Model
{
	// auto prefix models is set in index.php for namespacing purposes
	public static $_table_use_short_name = true;
}