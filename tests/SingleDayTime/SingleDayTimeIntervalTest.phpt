<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\SingleDayTime;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutableIntervalStringParser as ImmutableParser;
use Achse\Math\Interval\SingleDayTime\SingleDayTimeInterval;
use Achse\Math\Interval\SingleDayTime\SingleDayTimeIntervalStringParser as Parser;
use Tester\Assert;
use Tester\TestCase;



final class SingleDayTimeIntervalTest extends TestCase
{

	/**
	 * @dataProvider getDataForFromString
	 *
	 * @param string $expected
	 * @param string $left
	 * @param string $right
	 */
	public function testFromString(string $expected, string $left, string $right)
	{
		Assert::equal($expected, (string) SingleDayTimeInterval::fromString($left, $right));
	}



	/**
	 * @return string[][]
	 */
	public function getDataForFromString(): array
	{
		return [
			['[01:02:03, 04:05:06)', '01:02:03', '04:05:06'],
			['[00:00:00, 00:00:00)', '00:00:00', '00:00:00'],
			['[23:59:59, 00:00:00)', '23:59:59', '00:00:00'],
		];
	}



	/**
	 * @dataProvider getDataForIntersectionTest
	 *
	 * @param string $expected
	 * @param string $first
	 * @param string $second
	 */
	public function testIntersection(string $expected, string $first, string $second)
	{
		$intersection = Parser::parse($first)->intersection(Parser::parse($second));
		Assert::equal($expected, (string) $intersection);
		$intersection = Parser::parse($second)->intersection(Parser::parse($first));
		Assert::equal($expected, (string) $intersection);
	}



	/**
	 * @return string[][]
	 */
	public function getDataForIntersectionTest(): array
	{
		return [
			'Full day with itself' => ['[00:00:00, 00:00:00)', '[00:00:00, 00:00:00)', '[00:00:00, 00:00:00)'],
			['[01:00:00, 23:00:00]', '[00:00:00, 00:00:00)', '[01:00:00, 23:00:00]'],
			['[01:00:00, 00:00:00)', '[00:00:00, 00:00:00)', '[01:00:00, 00:00:00)'],
		];
	}



	/**
	 * @dataProvider getDataForIsEmpty
	 *
	 * @param bool $expected
	 * @param string $given
	 */
	public function testIsEmpty(bool $expected, string $given)
	{
		Assert::equal($expected, Parser::parse($given)->isEmpty());
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsEmpty(): array
	{
		return [
			[TRUE, '(00:00:00, 00:00:00)'],
			'Fill day interval is NOT empty' => [FALSE, '[00:00:00, 00:00:00)'],
		];
	}



	/**
	 * @dataProvider getDataForFromDateTimeIntervalTest
	 *
	 * @param string $expected
	 * @param string $input
	 */
	public function testFromDateTimeInterval(string $expected, string $input)
	{
		$dateTimeInterval = ImmutableParser::parse('[2015-05-10 12:13:14, 2015-05-12 21:22:23)');

		$interval = SingleDayTimeInterval::fromDateTimeInterval($dateTimeInterval, new DateTimeImmutable($input));
		Assert::equal($expected, (string) $interval);
	}



	/**
	 * @return string[][]
	 */
	public function getDataForFromDateTimeIntervalTest(): array
	{
		return [
			['[12:13:14, 23:59:59]', '2015-05-10'],
			['[00:00:00, 23:59:59]', '2015-05-11'],
			['[00:00:00, 21:22:23)', '2015-05-12'],
			'No intersection results in empty interval' => ['(00:00:00, 00:00:00)', '2015-05-13'],
		];
	}



	/**
	 * @dataProvider getDataForIsFollowedByWithPrecisionTest
	 *
	 * @param bool $expected
	 * @param string $firstString
	 * @param string $secondString
	 */
	public function testIsFollowedByWithPrecision(bool $expected, string $firstString, string $secondString)
	{
		$first = Parser::parse($firstString);
		$second = Parser::parse($secondString);
		Assert::same($expected, $first->isFollowedByWithPrecision($second, '+1 seconds'));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsFollowedByWithPrecisionTest(): array
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
		$dateTimeInterval = $singleDayInterval->toDaTeTimeInterval(new DateTimeImmutable('2015-10-11 20:21:22'));
		Assert::equal('[2015-10-11 01:02:03, 2015-10-11 04:05:06)', (string) $dateTimeInterval);
	}

}



(new SingleDayTimeIntervalTest())->run();
