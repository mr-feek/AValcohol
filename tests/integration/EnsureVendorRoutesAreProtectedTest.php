<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 5/14/16
 * Time: 6:27 PM
 */
class EnsureVendorRoutesAreProtectedTest extends TestCase
{
	use \Laravel\Lumen\Testing\DatabaseTransactions;
	protected $utils;
	protected $routes;

	public function setUp() {
		parent::setUp();
		$this->utils = new Utils();

		$this->routes = [
			'get' => [
				'orders/pending'
			],
			// synax == route name -> data
			'post' => [
				/*
				'' => [
					
				]
				*/
			]
		];
	}

	public function testErrorsAreThrownIfNoToken() {
		foreach($this->routes['get'] as $route) {
			$this->get($route);
			$this->assertEquals(401, $this->response->getStatusCode(), 'route did not return 401 unauthorized');
		}

		foreach($this->routes['post'] as $route) {
			$this->post($route);
			$this->assertEquals($this->response->getStatusCode(), 400);
		}
	}

	public function tearDown()
	{
		parent::tearDown();
		$this->utils->cleanUp();
	}
}