<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTime;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTime\DateTime;
use Achse\Math\Interval\DateTime\DateTimeIntervalStringParser as Parser;
use Tester\Assert;
use Tester\TestCase;



final class DateTimeIntervalTest extends TestCase
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
				'[2014-12-31 00:00:00, 2014-12-31 23:59:59)',
				'[2015-01-01 00:00:00, 2015-01-01 02:00:00)',
				'+1 second',
			],
			[
				FALSE,
				'[2015-01-01 00:00:00, 2015-01-01 02:00:00)',
				'[2014-12-31 00:00:00, 2014-12-31 23:59:59)',
				'+1 second',
			],

			[
				TRUE,
				'[2015-01-01 00:00:00, 2015-01-01 01:00:00)',
				'[2015-01-01 01:00:01, 2015-02-01 02:00:00)',
				'+1 second',
			],
			[
				FALSE,
				'[2015-01-01 01:00:01, 2015-02-01 02:00:00)',
				'[2015-01-01 00:00:00, 2015-01-01 01:00:00)',
				'+1 second',
			],

			[
				FALSE,
				'[2015-01-01 00:00:00, 2015-01-01 23:59:59)',
				'[2015-01-01 00:00:00, 2015-01-01 23:59:59)',
				'+1 second',
			],

			[
				FALSE,
				'[2015-01-01 00:00:00, 2015-01-01 01:38:00)',
				'[2015-01-01 01:39:00, 2015-01-01 02:00:00)',
				'+1 second',
			],
			[
				FALSE,
				'[2015-01-01 01:39:00, 2015-01-01 02:00:00)',
				'[2015-01-01 00:00:00, 2015-01-01 01:38:00)',
				'+1 minute',
			],
		];
	}



	public function testContaining()
	{
		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-02 01:00:00)');
		$second = Parser::parse('[2015-01-01 23:30:00, 2015-01-02 00:30:00)');
		Assert::true($first->isContaining($second));
		Assert::false($second->isContaining($first));

		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		$second = Parser::parse('[2015-01-01 00:30:00, 2015-01-01 01:00:00)');
		Assert::true($first->isContaining($second));
		Assert::false($second->isContaining($first));

		$one = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		Assert::true($one->isContaining($one));

		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		$second = Parser::parse('[2015-01-01 01:00:01, 2015-01-01 02:00:00)');
		Assert::false($first->isContaining($second));
	}



	public function testOverlapping()
	{
		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 22:30:00)');
		$second = Parser::parse('[2015-01-01 20:00:00, 2015-01-02 02:00:00)');
		Assert::true($first->isOverlappedFromRightBy($second));
		Assert::false($second->isOverlappedFromRightBy($first));

		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		$second = Parser::parse('[2015-01-01 00:30:00, 2015-01-01 02:00:00)');
		Assert::true($first->isOverlappedFromRightBy($second));
		Assert::false($second->isOverlappedFromRightBy($first));

		$one = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		Assert::false($one->isOverlappedFromRightBy($one));

		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		$second = Parser::parse('[2015-01-01 01:00:01, 2015-01-01 02:00:00)');
		Assert::false($first->isOverlappedFromRightBy($second));

		$first = Parser::parse('[2015-01-01 09:00:00, 2015-01-01 16:00:00)');
		$second = Parser::parse('[2015-01-01 15:00:00, 2015-01-01 22:00:00)');
		Assert::true($first->isOverlappedFromRightBy($second));
	}



	public function testIntersection()
	{
		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-02 01:00:00)');
		$second = Parser::parse('[2015-01-01 22:30:00, 2015-01-02 02:00:00)');
		$intersection = $first->getIntersection($second);
		Assert::equal('[2015-01-01 22:30:00, 2015-01-02 01:00:00)', (string) $intersection);
		$intersection = $second->getIntersection($first);
		Assert::equal('[2015-01-01 22:30:00, 2015-01-02 01:00:00)', (string) $intersection);

		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		$second = Parser::parse('[2015-01-01 00:30:00, 2015-01-01 02:00:00)');
		$intersection = $first->getIntersection($second);
		Assert::equal('[2015-01-01 00:30:00, 2015-01-01 01:00:00)', (string) $intersection);
		$intersection = $second->getIntersection($first);
		Assert::equal('[2015-01-01 00:30:00, 2015-01-01 01:00:00)', (string) $intersection);

		$one = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		$intersection = $one->getIntersection($one);
		Assert::equal((string) $one, (string) $intersection);

		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		$second = Parser::parse('[2015-01-01 05:00:00, 2015-01-01 10:00:00)');
		$intersection = $first->getIntersection($second);
		Assert::null($intersection);

		$first = Parser::parse('[2015-01-01 00:00:00, 2015-01-01 01:00:00)');
		$second = Parser::parse('[2015-01-01 00:30:00, 2015-01-01 00:45:00)');
		$intersection = $first->getIntersection($second);
		Assert::equal('[2015-01-01 00:30:00, 2015-01-01 00:45:00)', (string) $intersection);
		$intersection = $second->getIntersection($first);
		Assert::equal('[2015-01-01 00:30:00, 2015-01-01 00:45:00)', (string) $intersection);
	}



	public function testIsFollowedByAtMidnight()
	{
		$first = Parser::parse('[2015-03-04 20:00:00, 2015-03-04 23:59:59)');
		$second = Parser::parse('[2015-03-05 00:00:00, 2015-03-05 04:00:00)');

		Assert::true($first->isFollowedByAtMidnight($second));
		Assert::false($second->isFollowedByAtMidnight($first));

		$first = Parser::parse('[2015-03-03 20:00:00, 2015-03-03 23:59:59)');
		$secondShiftedByDay = Parser::parse('[2015-03-05 00:00:00, 2015-03-05 04:00:00)');

		Assert::false($first->isFollowedByAtMidnight($secondShiftedByDay));
	}



	public function testIsContainingDateTime()
	{
		$interval = Parser::parse('[2015-03-05 00:00:00, 2015-03-05 04:00:00)');

		Assert::true($interval->isContainingElement(new DateTime('2015-03-05 02:00:00')));
		Assert::false($interval->isContainingElement(new DateTime('2015-03-05 04:00:00')));
		Assert::false($interval->isContainingElement(new DateTime('2015-03-05 05:00:00')));
	}

}



(new DateTimeIntervalTest())->run();
