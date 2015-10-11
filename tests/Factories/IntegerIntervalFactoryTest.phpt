<?php

/**
 * @testCase
 */

namespace Tests\Factories;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\IntegerIntervalFactory;
use Achse\Math\Interval\Intervals\IntegerInterval;
use Tester\Assert;
use Tester\TestCase;



class IntegerIntervalFactoryTest extends TestCase
{

	public function testAll()
	{
		$result = IntegerIntervalFactory::create(1, Boundary::CLOSED, 4, Boundary::OPENED);
		Assert::type(IntegerInterval::class, $result);
		Assert::equal('[1, 4)', (string) $result);
	}

}



(new  IntegerIntervalFactoryTest())->run();
