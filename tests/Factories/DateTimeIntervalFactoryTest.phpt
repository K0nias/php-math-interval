<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval\Types;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\DateTimeIntervalFactory;
use Achse\Math\Interval\Intervals\DateTimeInterval;
use Tester\Assert;
use Tester\TestCase;



class DateTimeIntervalFactoryTest extends TestCase
{

	public function testAll()
	{
		$result = DateTimeIntervalFactory::create(
			'2015-10-11 00:00:00',
			Boundary::CLOSED,
			'2015-10-11  12:13:14',
			Boundary::OPENED
		);
		Assert::equal('[2015-10-11 00:00:00, 2015-10-11 12:13:14)', (string) $result);
	}

}



(new DateTimeIntervalFactoryTest())->run();
