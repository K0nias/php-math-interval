<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTimeImmutable;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutableIntervalStringParser as Parser;
use Tester\Assert;
use Tester\TestCase;



final class DateTimeImmutableIntervalTest extends TestCase
{

	/**
	 * @dataProvider getDataForConsolidationTest
	 *
	 * @param bool $expected
	 * @param string $first
	 * @param string $second
	 * @param string $precision
	 */
	public function testConsolidation(bool $expected, string $first, string $second, string $precision)
	{
		Assert::equal($expected, Parser::parse($first)->isFollowedByWithPrecision(Parser::parse($second), $precision));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForConsolidationTest(): array
	{
		return [
			[
				TRUE,
				'[2014-12-31T00:00:00+02:00, 2014-12-31T23:59:59+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-01T02:00:00+02:00)',
				'+1 second',
			],
			[
				FALSE,
				'[2015-01-01T00:00:00+02:00, 2015-01-01T02:00:00+02:00)',
				'[2014-12-31T00:00:00+02:00, 2014-12-31T23:59:59+02:00)',
				'+1 second',
			],

			[
				TRUE,
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)',
				'[2015-01-01T01:00:01+02:00, 2015-02-01T02:00:00+02:00)',
				'+1 second',
			],
			[
				FALSE,
				'[2015-01-01T01:00:01+02:00, 2015-02-01T02:00:00+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)',
				'+1 second',
			],

			[
				FALSE,
				'[2015-01-01T00:00:00+02:00, 2015-01-01T23:59:59+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-01T23:59:59+02:00)',
				'+1 second',
			],

			[
				FALSE,
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:38:00+02:00)',
				'[2015-01-01T01:39:00+02:00, 2015-01-01T02:00:00+02:00)',
				'+1 second',
			],
			[
				FALSE,
				'[2015-01-01T01:39:00+02:00, 2015-01-01T02:00:00+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:38:00+02:00)',
				'+1 minute',
			],
		];
	}



	/**
	 * @dataProvider getDataForIsContainingTest
	 *
	 * @param bool $expected
	 * @param string $first
	 * @param string $second
	 */
	public function testIsContaining(bool $expected, string $first, string $second)
	{
		Assert::equal($expected, Parser::parse($first)->isContaining(Parser::parse($second)));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsContainingTest(): array
	{
		return [
			[TRUE, '[2015-01-01T00:00:00+02:00, 2015-01-02T01:00:00+02:00)', '[2015-01-01T23:30:00+02:00, 2015-01-02T00:30:00+02:00)'],
			[FALSE, '[2015-01-01T23:30:00+02:00, 2015-01-02T00:30:00+02:00)', '[2015-01-01T00:00:00+02:00, 2015-01-02T01:00:00+02:00)'],

			[TRUE, '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T00:30:00+02:00, 2015-01-01T01:00:00+02:00)'],
			[FALSE, '[2015-01-01T00:30:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)'],

			[TRUE, '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)'],

			[FALSE, '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T01:00:01+02:00, 2015-01-01T02:00:00+02:00)'],
		];
	}



	/**
	 * @dataProvider getDataForOverlapping
	 *
	 * @param bool $expected
	 * @param string $left
	 * @param string $right
	 */
	public function testOverlapping(bool $expected, string $left, string $right)
	{
		Assert::equal($expected, Parser::parse($left)->isOverlappedFromRightBy(Parser::parse($right)));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForOverlapping(): array
	{
		return [
			[TRUE, '[2015-01-01T00:00:00+02:00, 2015-01-01T22:30:00+02:00)', '[2015-01-01T20:00:00+02:00, 2015-01-02T02:00:00+02:00)'],
			[FALSE, '[2015-01-01T20:00:00+02:00, 2015-01-02T02:00:00+02:00)', '[2015-01-01T00:00:00+02:00, 2015-01-01T22:30:00+02:00)'],

			[TRUE, '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T00:30:00+02:00, 2015-01-01T02:00:00+02:00)'],
			[FALSE, '[2015-01-01T00:30:00+02:00, 2015-01-01T02:00:00+02:00)', '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)'],

			[FALSE, '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)'],

			[TRUE, '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T00:30:00+02:00, 2015-01-01T02:00:00+02:00)'],
			[FALSE, '[2015-01-01T00:30:00+02:00, 2015-01-01T02:00:00+02:00)', '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)'],

			[FALSE, '[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T01:00:01+02:00, 2015-01-01T02:00:00+02:00)'],

			[TRUE, '[2015-01-01T09:00:00+02:00, 2015-01-01T16:00:00+02:00)', '[2015-01-01T15:00:00+02:00, 2015-01-01T22:00:00+02:00)'],
		];
	}



	/**
	 * @dataProvider getDataForIntersectionWhenIsEmpty
	 *
	 * @param string $first
	 * @param string $second
	 */
	public function testIntersectionWhenIsEmpty(string $first, string $second)
	{
		$intersection = Parser::parse($first)->intersection(Parser::parse($second));
		Assert::true($intersection->isEmpty());
	}



	/**
	 * @return string[][]
	 */
	public function getDataForIntersectionWhenIsEmpty(): array
	{
		return [
			['[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)', '[2015-01-01T05:00:00+02:00, 2015-01-01T10:00:00+02:00)'],
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
			[
				'[2015-01-01T22:30:00+02:00, 2015-01-02T01:00:00+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-02T01:00:00+02:00)',
				'[2015-01-01T22:30:00+02:00, 2015-01-02T02:00:00+02:00)',
			],
			[
				'[2015-01-01T00:30:00+02:00, 2015-01-01T01:00:00+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)',
				'[2015-01-01T00:30:00+02:00, 2015-01-01T02:00:00+02:00)',
			],
			[
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)',
			],
			[
				'[2015-01-01T00:30:00+02:00, 2015-01-01T00:45:00+02:00)',
				'[2015-01-01T00:00:00+02:00, 2015-01-01T01:00:00+02:00)',
				'[2015-01-01T00:30:00+02:00, 2015-01-01T00:45:00+02:00)',
			],
		];
	}



	/**
	 * @dataProvider getDataForIsFollowedByMidnight
	 *
	 * @param bool $expected
	 * @param string $first
	 * @param string $second
	 */
	public function testIsFollowedByAtMidnight(bool $expected, string $first, string $second)
	{
		Assert::equal($expected, Parser::parse($first)->isFollowedByAtMidnight(Parser::parse($second)));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsFollowedByMidnight(): array
	{
		return [
			[TRUE, '[2015-03-04T20:00:00+02:00, 2015-03-04T23:59:59+02:00)', '[2015-03-05T00:00:00+02:00, 2015-03-05T04:00:00+02:00)'],
			[FALSE, '[2015-03-05T00:00:00+02:00, 2015-03-05T04:00:00+02:00)', '[2015-03-04T20:00:00+02:00, 2015-03-04T23:59:59+02:00)'],

			[FALSE, '[2015-03-03T20:00:00+02:00, 2015-03-03T23:59:59+02:00)', '[2015-03-05T00:00:00+02:00, 2015-03-05T04:00:00+02:00)'],
		];
	}



	public function testIsContainingDateTimeImmutable()
	{
		$interval = Parser::parse('[2015-03-05T00:00:00+02:00, 2015-03-05T04:00:00+02:00)');

		Assert::true($interval->isContainingElement(new DateTimeImmutable('2015-03-05T02:00:00+02:00')));
		Assert::false($interval->isContainingElement(new DateTimeImmutable('2015-03-05T04:00:00+02:00')));
		Assert::false($interval->isContainingElement(new DateTimeImmutable('2015-03-05T05:00:00+02:00')));
	}

}



(new DateTimeImmutableIntervalTest())->run();
