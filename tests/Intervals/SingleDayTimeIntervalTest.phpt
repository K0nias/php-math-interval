<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\DateTimeBoundaryFactory;
use Achse\Math\Interval\Intervals\DateTimeInterval;
use Achse\Math\Interval\Intervals\SingleDayTimeInterval;
use Achse\Math\Interval\Types\DateTime;
use Nette\InvalidArgumentException;
use Tester\Assert;
use Tester\TestCase;



class SingleDayTimeIntervalTest extends TestCase
{

	public function testFromString()
	{
		$interval = SingleDayTimeInterval::fromString('01:02:03', '04:05:06');
		Assert::equal('[01:02:03, 04:05:06)', (string) $interval);
	}



	public function testFromDateTimeInterval()
	{
		$dateTimeInterval = new DateTimeInterval(
			DateTimeBoundaryFactory::create('2015-05-10 12:13:14', Boundary::CLOSED),
			DateTimeBoundaryFactory::create('2015-05-12 21:22:23', Boundary::OPENED)
		);

		$interval = SingleDayTimeInterval::fromDateTimeInterval($dateTimeInterval, new DateTime('2015-05-10'));
		Assert::equal('[12:13:14, 23:59:59]', (string) $interval);

		$interval = SingleDayTimeInterval::fromDateTimeInterval($dateTimeInterval, new DateTime('2015-05-11'));
		Assert::equal('[00:00:00, 23:59:59]', (string) $interval);

		$interval = SingleDayTimeInterval::fromDateTimeInterval($dateTimeInterval, new DateTime('2015-05-12'));
		Assert::equal('[00:00:00, 21:22:23)', (string) $interval);

		Assert::exception(
			function () use ($dateTimeInterval) {
				SingleDayTimeInterval::fromDateTimeInterval($dateTimeInterval, new DateTime('2015-05-13'));
			},
			InvalidArgumentException::class
		);
	}



	public function testIsFollowedBy()
	{
		$toMidnight = SingleDayTimeInterval::fromString('23:50:00', '23:59:59');
		$fromMidnight = SingleDayTimeInterval::fromString('00:00:00', '00:05:00');
		Assert::false($toMidnight->isFollowedBy($fromMidnight));
	}

}



(new SingleDayTimeIntervalTest())->run();
