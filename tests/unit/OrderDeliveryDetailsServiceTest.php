<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/22/16
 * Time: 4:44 PM
 */
class OrderDeliveryDetailsServiceTest extends TestCase
{
	protected $service;
	public function setUp()
	{
		parent::setUp();
		$this->service = $this->app['App\Http\Repositories\Interfaces\OrderDeliveryDetailsInterface'];
	}

	public function testCannotSaveImageWithInvalidExtension() {
		$invalidBase64Data = 'data:image/php;base64,iVBORw0KGgoAAAANSUhEUgAAAXcPgogICAgICAgICAgICAgImVBZ2VudD'; // we really only care about beginning
		
		$exception = null;
		try {
			$this->service->savePhoto($invalidBase64Data);
		} catch (\App\Exceptions\APIException $e) {
			$exception = $e;
		}

		$this->assertNotNull($exception, 'No exception was thrown for an invalid base 64 extension');
	}
}