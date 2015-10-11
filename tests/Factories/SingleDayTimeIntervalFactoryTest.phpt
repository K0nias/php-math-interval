<?php

/**
 * @testCase
 */

namespace Tests\Factories;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\SingleDayTimeIntervalFactory;
use Achse\Math\Interval\Intervals\SingleDayTimeInterval;
use Tester\Assert;
use Tester\TestCase;



class SingleDayTimeIntervalFactoryTest extends TestCase
{

	public function testAll()
	{
		$result = SingleDayTimeIntervalFactory::create('00:00:00', Boundary::CLOSED, '12:13:14', Boundary::OPENED);
		Assert::type(SingleDayTimeInterval::class, $result);
		Assert::equal('[00:00:00, 12:13:14)', (string) $result);
	}

}



(new SingleDayTimeIntervalFactoryTest())->run();
