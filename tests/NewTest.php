<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 1/25/16
 * Time: 12:26 AM
 */
class NewTest extends TestCase
{
	public function testPlz() {
		$this->get('/user/1');

		$this->seeJson(['id' => 1]);
	}
}