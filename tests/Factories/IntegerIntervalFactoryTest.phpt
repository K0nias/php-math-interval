<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\Types;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\IntegerIntervalFactory;
use Tester\Assert;
use Tester\TestCase;



class IntegerIntervalFactoryTest extends TestCase
{

	public function testAll()
	{
		$result = IntegerIntervalFactory::create(1, Boundary::CLOSED, 4, Boundary::OPENED);
		Assert::equal('[1, 4)', (string) $result);
	}

}



(new  IntegerIntervalFactoryTest())->run();
