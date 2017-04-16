<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\SingleDay;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\SingleDay\SingleDayTimeBoundaryFactory;
use Tester\Assert;
use Tester\TestCase;



final class SingleDayTimeBoundaryFactoryTest extends TestCase
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
