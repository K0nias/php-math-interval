<?php

/**
 * @testCase
 */

namespace Tests\Factories;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\DateTimeBoundaryFactory;
use Achse\Math\Interval\Factories\SingleDayTimeBoundaryFactory;
use Tester\Assert;
use Tester\TestCase;



class SingleDayTimeBoundaryFactoryTest extends TestCase
{

	public function testAll()
	{
		Assert::equal(
			'[12:13:14]',
			(string) SingleDayTimeBoundaryFactory::create('2015-10-11 12:13:14', Boundary::CLOSED)
		);
		Assert::equal(
			'[01:02:03]',
			(string) SingleDayTimeBoundaryFactory::create('01:02:03', Boundary::CLOSED)
		);
	}

}



(new SingleDayTimeBoundaryFactoryTest())->run();
