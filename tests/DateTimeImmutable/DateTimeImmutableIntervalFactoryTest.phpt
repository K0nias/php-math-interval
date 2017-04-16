<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\Integer;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutableIntervalFactory;
use Tester\Assert;
use Tester\TestCase;



final class DateTimeImmutableIntervalFactoryTest extends TestCase
{

	public function testAll()
	{
		$result = DateTimeImmutableIntervalFactory::create(
			'2015-10-11 00:00:00',
			Boundary::CLOSED,
			'2015-10-11  12:13:14',
			Boundary::OPENED
		);
		Assert::equal('[2015-10-11 00:00:00, 2015-10-11 12:13:14)', (string) $result);
	}

}



(new DateTimeImmutableIntervalFactoryTest())->run();
