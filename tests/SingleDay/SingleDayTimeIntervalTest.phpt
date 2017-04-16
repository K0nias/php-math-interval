<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\SingleDay;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTime\DateTime;
use Achse\Math\Interval\DateTime\DateTimeBoundary;
use Achse\Math\Interval\DateTime\DateTimeInterval;
use Achse\Math\Interval\DateTime\DateTimeIntervalStringParser;
use Achse\Math\Interval\SingleDay\SingleDayTimeInterval;
use Achse\Math\Interval\SingleDay\SingleDayTimeIntervalStringParser as Parser;
use InvalidArgumentException;
use Tester\Assert;
use Tester\TestCase;



final class SingleDayTimeIntervalTest extends TestCase
{

	public function testFromString()
	{
		$interval = SingleDayTimeInterval::fromString('01:02:03', '04:05:06');
		Assert::equal('[01:02:03, 04:05:06)', (string) $interval);
	}



	public function testFromDateTimeInterval()
	{
		$dateTimeInterval = DateTimeIntervalStringParser::parse('[2015-05-10 12:13:14, 2015-05-12 21:22:23)');

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



	/**
	 * @dataProvider getDataForIsFollowedByTest
	 *
	 * @param bool $expected
	 * @param string $firstString
	 * @param string $secondString
	 */
	public function testIsFollowedBy(bool $expected, string $firstString, string $secondString)
	{
		$first = Parser::parse($firstString);
		$second = Parser::parse($secondString);
		Assert::same($expected, $first->isFollowedBy($second));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsFollowedByTest(): array
	{
		return [
			[FALSE, '[23:50:00, 23:59:59]', '[00:00:00, 00:05:00]'],
			[FALSE, '[00:00:00, 00:05:00]', '[23:50:00, 23:59:59]'],
			[TRUE, '[01:00:00, 02:00:00]', '[02:00:01, 23:59:59]'],
		];
	}



	public function testToDateTimeInterval()
	{
		$singleDayInterval = SingleDayTimeInterval::fromString('01:02:03', '04:05:06');
		$dateTimeInterval = $singleDayInterval->toDaTeTimeInterval(new DateTime('2015-10-11 20:21:22'));
		Assert::type(DateTimeInterval::class, $dateTimeInterval);
		Assert::type(DateTimeBoundary::class, $dateTimeInterval->getLeft());
		Assert::type(DateTimeBoundary::class, $dateTimeInterval->getRight());
		Assert::equal('[2015-10-11 01:02:03, 2015-10-11 04:05:06)', (string) $dateTimeInterval);
	}

}



(new SingleDayTimeIntervalTest())->run();
