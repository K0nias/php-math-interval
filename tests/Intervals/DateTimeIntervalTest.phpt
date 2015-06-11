<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Intervals\DateTimeInterval;
use Achse\Math\Interval\Intervals\Interval;
use Achse\Math\Interval\Types\Comparison\IntervalUtils;
use Achse\Math\Interval\Types\DateTime;
use Tester\Assert;
use Tester\TestCase;



class DateTimeIntervalTest extends TestCase
{

	public function testConsolidation()
	{
		$first = DateTimeInterval::fromString('2014-12-31 00:00:00', '2014-12-31 23:59:59');
		$second = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 02:00:00');
		Assert::true($first->isFollowedBy($second, IntervalUtils::PRECISION_ON_SECOND));
		Assert::false($second->isFollowedBy($first, IntervalUtils::PRECISION_ON_SECOND));

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 01:00:01', '2015-02-01 02:00:00');
		Assert::true($first->isFollowedBy($second, IntervalUtils::PRECISION_ON_SECOND));
		Assert::false($second->isFollowedBy($first, IntervalUtils::PRECISION_ON_SECOND));

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 01:00:02', '2015-01-01 02:00:00');
		Assert::false($first->isFollowedBy($second, IntervalUtils::PRECISION_ON_SECOND));

		$allDay = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 23:59:59');
		Assert::false($allDay->isFollowedBy($allDay, IntervalUtils::PRECISION_ON_SECOND));

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:38:00');
		$second = DateTimeInterval::fromString('2015-01-01 01:39:00', '2015-01-01 02:00:00');
		Assert::false($first->isFollowedBy($second, IntervalUtils::PRECISION_ON_SECOND));
		Assert::true($first->isFollowedBy($second, IntervalUtils::PRECISION_ON_MINUTE));
	}



	public function testContaining()
	{
		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-02 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 23:30:00', '2015-01-02 00:30:00');
		Assert::true($first->isContaining($second));
		Assert::false($second->isContaining($first));

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 00:30:00', '2015-01-01 01:00:00');
		Assert::true($first->isContaining($second));
		Assert::false($second->isContaining($first));

		$one = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		Assert::true($one->isContaining($one));

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 01:00:01', '2015-01-01 02:00:00');
		Assert::false($first->isContaining($second));
	}



	public function testOverlapping()
	{
		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 22:30:00');
		$second = DateTimeInterval::fromString('2015-01-01 20:00:00', '2015-01-02 02:00:00');
		Assert::true($first->isOverlappedFromRightBy($second));
		Assert::false($second->isOverlappedFromRightBy($first));

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 00:30:00', '2015-01-01 02:00:00');
		Assert::true($first->isOverlappedFromRightBy($second));
		Assert::false($second->isOverlappedFromRightBy($first));

		$one = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		Assert::false($one->isOverlappedFromRightBy($one));

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 01:00:01', '2015-01-01 02:00:00');
		Assert::false($first->isOverlappedFromRightBy($second));

		$first = DateTimeInterval::fromString('2015-01-01 09:00:00', '2015-01-01 16:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 15:00:00', '2015-01-01 22:00:00');
		Assert::true($first->isOverlappedFromRightBy($second));
	}



	public function testIntersection()
	{
		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-02 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 22:30:00', '2015-01-02 02:00:00');
		$intersection = $first->getIntersection($second);
		Assert::equal('[2015-01-01 22:30:00, 2015-01-02 01:00:00)', $intersection->getString());
		$intersection = $second->getIntersection($first);
		Assert::equal('[2015-01-01 22:30:00, 2015-01-02 01:00:00)', $intersection->getString());

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 00:30:00', '2015-01-01 02:00:00');
		$intersection = $first->getIntersection($second);
		Assert::equal('[2015-01-01 00:30:00, 2015-01-01 01:00:00)', $intersection->getString());
		$intersection = $second->getIntersection($first);
		Assert::equal('[2015-01-01 00:30:00, 2015-01-01 01:00:00)', $intersection->getString());

		$one = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$intersection = $one->getIntersection($one);
		Assert::equal($one->getString(), $intersection->getString());

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 05:00:00', '2015-01-01 10:00:00');
		$intersection = $first->getIntersection($second);
		Assert::null($intersection);

		$first = DateTimeInterval::fromString('2015-01-01 00:00:00', '2015-01-01 01:00:00');
		$second = DateTimeInterval::fromString('2015-01-01 00:30:00', '2015-01-01 00:45:00');
		$intersection = $first->getIntersection($second);
		Assert::equal('[2015-01-01 00:30:00, 2015-01-01 00:45:00)', $intersection->getString());
		$intersection = $second->getIntersection($first);
		Assert::equal('[2015-01-01 00:30:00, 2015-01-01 00:45:00)', $intersection->getString());
	}



	public function testIsFollowedByAtMidnight()
	{
		$first = new DateTimeInterval(
			new DateTime('2015-03-04 20:00:00'), Interval::CLOSED, new DateTime('2015-03-04 23:59:59'), Interval::OPENED
		);

		$second = new DateTimeInterval(
			new DateTime('2015-03-05 00:00:00'), Interval::CLOSED, new DateTime('2015-03-05 04:00:00'), Interval::OPENED
		);

		Assert::true($first->isFollowedByAtMidnight($second));
		Assert::false($second->isFollowedByAtMidnight($first));

		$first = new DateTimeInterval(
			new DateTime('2015-03-03 20:00:00'), Interval::CLOSED, new DateTime('2015-03-03 23:59:59'), Interval::OPENED
		);

		$secondShiftedByDay = new DateTimeInterval(
			new DateTime('2015-03-05 00:00:00'), Interval::CLOSED, new DateTime('2015-03-05 04:00:00'), Interval::OPENED
		);

		Assert::false($first->isFollowedByAtMidnight($secondShiftedByDay));
	}



	public function testIsContainingDateTime()
	{
		$interval = new DateTimeInterval(
			new DateTime('2015-03-05 00:00:00'), Interval::CLOSED, new DateTime('2015-03-05 04:00:00'), Interval::OPENED
		);
		Assert::true($interval->isContainingElement(new DateTime('2015-03-05 02:00:00')));
		Assert::false($interval->isContainingElement(new DateTime('2015-03-05 04:00:00')));
		Assert::false($interval->isContainingElement(new DateTime('2015-03-05 05:00:00')));
	}

}



(new DateTimeIntervalTest())->run();
