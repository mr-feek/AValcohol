<?php

/**
 * Created by PhpStorm.
 * User: Feek
 * Date: 6/29/16
 * Time: 12:09 PM
 */
class SiteStatusTest extends TestCase
{
	protected $repo;

	public function setUp()
	{
		parent::setUp();
		$this->repo = app()->make('App\Http\Repositories\Interfaces\SiteStatusInterface');
	}

	public function testRepoDeliveryHours() {
		$this->shouldDeliver('00:01', true);
		$this->shouldDeliver('01:00', true);
		$this->shouldDeliver('01:59', true);
		$this->shouldDeliver('02:00', false);
		$this->shouldDeliver('03:00', false);
		$this->shouldDeliver('04:00', false);
		$this->shouldDeliver('05:00', false);
		$this->shouldDeliver('06:00', false);
		$this->shouldDeliver('07:00', false);
		$this->shouldDeliver('08:00', false);
		$this->shouldDeliver('09:00', false);
		$this->shouldDeliver('10:00', false);
		$this->shouldDeliver('11:00', false);
		$this->shouldDeliver('12:00', false);
		$this->shouldDeliver('13:00', false);
		$this->shouldDeliver('14:00', false);
		$this->shouldDeliver('15:00', false);
		$this->shouldDeliver('16:00', false);
		$this->shouldDeliver('17:00', false);
		$this->shouldDeliver('18:00', true);
		$this->shouldDeliver('19:00', true);
		$this->shouldDeliver('20:00', true);
		$this->shouldDeliver('21:00', true);
		$this->shouldDeliver('22:00', true);
		$this->shouldDeliver('23:00', true);
	}

	protected function shouldDeliver($time, $expected) {
		$response = $this->repo->isOpenAt($time);

		$this->assertEquals($expected, $response);
	}
}