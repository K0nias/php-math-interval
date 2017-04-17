<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTime;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\DateTime\DateTimeIntervalFactory;
use Tester\Assert;
use Tester\TestCase;



final class DateTimeIntervalFactoryTest extends TestCase
{

	public function testAll()
	{
		$result = DateTimeIntervalFactory::create(
			new \DateTime('2015-10-11 00:00:00'),
			Boundary::CLOSED,
			new \DateTime('2015-10-11  12:13:14'),
			Boundary::OPENED
		);
		Assert::equal('[2015-10-11 00:00:00, 2015-10-11 12:13:14)', (string) $result);
	}

}



(new DateTimeIntervalFactoryTest())->run();
