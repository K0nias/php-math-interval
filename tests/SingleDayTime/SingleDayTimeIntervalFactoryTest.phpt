<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval\SingleDayTime;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\SingleDayTime\SingleDayTimeIntervalFactory;
use Tester\Assert;
use Tester\TestCase;



final class SingleDayTimeIntervalFactoryTest extends TestCase
{

	public function testAll()
	{
		$result = SingleDayTimeIntervalFactory::create('00:00:00', Boundary::CLOSED, '12:13:14', Boundary::OPENED);
		Assert::equal('[00:00:00, 12:13:14)', (string) $result);
	}

}



(new SingleDayTimeIntervalFactoryTest())->run();
