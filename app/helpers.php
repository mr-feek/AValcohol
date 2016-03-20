<?php
/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/18/16
 * Time: 8:13 PM
 */

if ( ! function_exists('config_path'))
{
	/**
	 * Get the configuration path.
	 *
	 * @param  string $path
	 * @return string
	 */
	function config_path($path = '')
	{
		return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
	}
}