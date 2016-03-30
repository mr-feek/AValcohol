<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 3/29/16
 * Time: 11:08 PM
 */
class ConfigTest extends TestCase
{
	public function testCorrectTimezone() {
		$time = date_default_timezone_get();
		$this->assertEquals($time, 'America/New_York');
	}
}