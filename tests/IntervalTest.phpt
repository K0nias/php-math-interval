<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval;

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/DummyInt.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Integer\Integer;
use Achse\Math\Interval\Integer\IntegerBoundary;
use Achse\Math\Interval\Integer\IntegerInterval;
use Achse\Math\Interval\Integer\IntegerIntervalStringParser as Parser;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalRangesInvalidException;
use Tester\Assert;
use Tester\TestCase;



final class IntervalTest extends TestCase
{

	public function testIntervalCanBeUsedStandAlone()
	{

		$interval = new Interval(
			new Boundary(new DummyInt(5), Boundary::CLOSED),
			new Boundary(new DummyInt(6), Boundary::CLOSED)
		);

		Assert::equal('[5, 6]', (string) $interval);
	}



	public function testErrors()
	{
		Assert::exception(
			function () {
				Parser::parse('[5, 0]');
			},
			IntervalRangesInvalidException::class,
			'Left endpoint cannot be greater then Right endpoint.'
		);
	}



	/**
	 * @dataProvider getDataForOpenedClosedTest
	 *
	 * @param bool $expectedOpened
	 * @param bool $expectedClosed
	 * @param string $interval
	 */
	public function testOpenedClosed(bool $expectedOpened, bool $expectedClosed, string $interval)
	{
		Assert::equal($expectedOpened, Parser::parse($interval)->isOpened());
		Assert::equal($expectedClosed, Parser::parse($interval)->isClosed());
	}



	/**
	 * @return bool[][]|string[]
	 */
	public function getDataForOpenedClosedTest(): array
	{
		return [
			[FALSE, TRUE, '[1, 4]'],
			[TRUE, FALSE, '(1, 4)'],
			[FALSE, FALSE, '[1, 2)'],
			[FALSE, FALSE, '(1, 2]'],
		];
	}



	/**
	 * @dataProvider getDataForIsDegenerate
	 *
	 * @param bool $expected
	 * @param string $given
	 */
	public function testIsDegenerate(bool $expected, string $given)
	{
		Assert::equal($expected, Parser::parse($given)->isDegenerate());
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsDegenerate(): array
	{
		return [
			[TRUE, '[1, 1]'],
			[FALSE, '(1, 1)'],
			[FALSE, '(1, 1]'],

			[FALSE, '[1, 4]'],
			[FALSE, '[1, 4)'],
			[FALSE, '(1, 4]'],
			[FALSE, '(1, 4)'],
		];
	}



	/**
	 * @dataProvider getDataForIsProper
	 *
	 * @param bool $expected
	 * @param string $given
	 */
	public function testIsProper(bool $expected, string $given)
	{
		Assert::equal($expected, Parser::parse($given)->isProper());
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsProper(): array
	{
		return [
			[FALSE, '[1, 1]'],
			[FALSE, '(1, 1)'],
			[FALSE, '(1, 1]'],

			[TRUE, '[1, 4]'],
			[TRUE, '[1, 4)'],
			[TRUE, '(1, 4]'],
			[TRUE, '(1, 4)'],
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
			[FALSE, '[1, 1]'],
			[TRUE, '(1, 1)'],
			[TRUE, '(1, 1]'],

			[FALSE, '[1, 4]'],
			[FALSE, '[1, 4)'],
			[FALSE, '(1, 4]'],
			[FALSE, '(1, 4)'],
		];
	}



	/**
	 * @dataProvider getDataForIsContainingElement
	 *
	 * @param bool $expected
	 * @param string $interval
	 * @param int $element
	 */
	public function testIsContainingElement(bool $expected, string $interval, Integer $element)
	{
		Assert::equal($expected, Parser::parse($interval)->isContainingElement($element));
	}



	/**
	 * @return bool[][]|string[][]|Integer
	 */
	public function getDataForIsContainingElement(): array
	{
		return [
			[TRUE, '[1, 2]', new Integer(1)],
			[TRUE, '[1, 2]', new Integer(2)],

			[FALSE, '(1, 2)', new Integer(1)],
			[FALSE, '(1, 2)', new Integer(2)],

			[TRUE, '[1, 3)', new Integer(1)],
			[TRUE, '[1, 3)', new Integer(2)],
			[FALSE, '[1, 3)', new Integer(3)],

			[FALSE, '(1, 3]', new Integer(1)],
			[TRUE, '(1, 3]', new Integer(2)],
			[TRUE, '(1, 3]', new Integer(3)],
		];
	}



	public function testIsContaining()
	{

		// [1, 4] contains (1, 4)
		Assert::true(Parser::parse('[1, 4]')->isContaining(Parser::parse('(1, 4)')));
		// (1, 4) NOT contains [1, 4]
		Assert::false(Parser::parse('(1, 4)')->isContaining(Parser::parse('[1, 4]')));

		// [1, 4] contains [2, 3]
		Assert::true(Parser::parse('[1, 4]')->isContaining(Parser::parse('[2, 3]')));
		// [2, 3] NOT contains [1, 4]
		Assert::false(Parser::parse('[2, 3]')->isContaining(Parser::parse('[1, 4]')));

		// [1, 4] contains (2, 3)
		Assert::true(Parser::parse('[1, 4]')->isContaining(Parser::parse('(2, 3)')));
		// (2, 3) NOT contains [1, 4]
		Assert::false(Parser::parse('(2, 3)')->isContaining(Parser::parse('[1, 4]')));


		// (1, 4) NOT contains [3, 4]
		Assert::false(Parser::parse('(1, 4)')->isContaining(Parser::parse('[3, 4]')));
		// (1, 4) NOT contains [1, 2]
		Assert::false(Parser::parse('(1, 4)')->isContaining(Parser::parse('[1, 2]')));

		$left = Parser::parse('[1, 3]');
		$right = Parser::parse('[3, 4]');

		Assert::false($left->isContaining($right));
		Assert::false($right->isContaining($left));
	}



	public function testIsOverlappedFromRightBy()
	{
		Assert::true(Parser::parse('[1, 2]')->isOverlappedFromRightBy(Parser::parse('[2, 3]')));
		Assert::false(Parser::parse('[2, 3]')->isOverlappedFromRightBy(Parser::parse('[1, 2]')));

		// (1, 2) ~ [2, 3]
		Assert::false(Parser::parse('(1, 2)')->isOverlappedFromRightBy(Parser::parse('[2, 3]')));
		// [1, 2] ~ (2, 3)
		Assert::false(Parser::parse('[1, 2]')->isOverlappedFromRightBy(Parser::parse('(2, 3)')));

		Assert::true(Parser::parse('(1, 3)')->isOverlappedFromRightBy(Parser::parse('(2, 4)')));
	}



	public function testIsColliding()
	{
		Assert::true(Parser::parse('[1, 2]')->isColliding(Parser::parse('[2, 3]')));
		Assert::true(Parser::parse('[2, 3]')->isColliding(Parser::parse('[1, 2]')));

		Assert::false(Parser::parse('[1, 2]')->isColliding(Parser::parse('(2, 3)')));
		Assert::false(Parser::parse('(1, 2)')->isColliding(Parser::parse('[2, 3]')));

		Assert::true(Parser::parse('(1, 3)')->isColliding(Parser::parse('(2, 4)')));
		Assert::true(Parser::parse('(2, 4)')->isColliding(Parser::parse('(1, 3)')));
	}



	public function testGetIntersection()
	{
		$intervalTwoTwoClosed = Parser::parse('[2, 2]');

		$intersection = Parser::parse('[1, 2]')->intersection(Parser::parse('[2, 3]'));
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		$intersection = Parser::parse('[2, 3]')->intersection(Parser::parse('[1, 2]'));
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		Assert::null(Parser::parse('[1, 2]')->intersection(Parser::parse('(2, 3)')));
		Assert::null(Parser::parse('(1, 2)')->intersection(Parser::parse('[2, 3]')));

		$intervalTwoThreeOpened = Parser::parse('(2, 3)');

		// (1, 3) ∩ (2, 4) ⟺ (2, 3)
		$intersection = Parser::parse('(1, 3)')->intersection(Parser::parse('(2, 4)'));
		$this->assertInterval($intervalTwoThreeOpened, $intersection);

		$intersection = Parser::parse('(2, 4)')->intersection(Parser::parse('(1, 3)'));
		$this->assertInterval($intervalTwoThreeOpened, $intersection);
	}



	public function testDifference()
	{
		// [1, 4] \ [0, 5]
		$diff = Parser::parse('[1, 4]')->difference(Parser::parse('[0, 5]'));
		Assert::count(0, $diff);

		// [1, 4] \ [1, 4]
		$diff = Parser::parse('[1, 4]')->difference(Parser::parse('[1, 4]'));
		Assert::count(0, $diff);

		// (1, 4) \ (1, 4)
		$diff = Parser::parse('(1, 4)')->difference(Parser::parse('(1, 4)'));
		Assert::count(0, $diff);

		// [1, 4] \ [2, 4]
		$diff = Parser::parse('[1, 4]')->difference(Parser::parse('[2, 4]'));
		Assert::count(1, $diff);
		Assert::equal('[1, 2)', (string) reset($diff));

		// [1, 4] \ [1, 2]
		$diff = Parser::parse('[1, 4]')->difference(Parser::parse('[1, 2]'));
		Assert::count(1, $diff);
		Assert::equal('(2, 4]', (string) reset($diff));

		// [1, 4] \ (2, 4)
		$diff = Parser::parse('[1, 4]')->difference(Parser::parse('(2, 4)'));
		Assert::count(2, $diff);
		Assert::equal('[1, 2]', (string) $diff[0]);
		Assert::equal('[4, 4]', (string) $diff[1]);

		// [1, 4] \ (1, 2)
		$diff = Parser::parse('[1, 4]')->difference(Parser::parse('(1, 2)'));
		Assert::count(2, $diff);
		Assert::equal('[1, 1]', (string) $diff[0]);
		Assert::equal('[2, 4]', (string) $diff[1]);

		// [1, 4] \ [2, 3]
		$diff = Parser::parse('[1, 4]')->difference(Parser::parse('[2, 3]'));
		Assert::count(2, $diff);
		Assert::equal('[1, 2)', (string) $diff[0]);
		Assert::equal('(3, 4]', (string) $diff[1]);

		// [1, 4] \ (2, 3)
		$diff = Parser::parse('[1, 4]')->difference(Parser::parse('(2, 3)'));
		Assert::count(2, $diff);
		Assert::equal('[1, 2]', (string) $diff[0]);
		Assert::equal('[3, 4]', (string) $diff[1]);
	}



	/**
	 * @dataProvider getDataForGetUnion
	 *
	 * @param string[] $expected
	 * @param string $first
	 * @param string $second
	 */
	public function testUnion(array $expected, string $first, string $second)
	{
		$union = Parser::parse($first)->union(Parser::parse($second));
		Assert::equal($expected, $this->intervalArrayToString($union));
	}



	/**
	 * @return array
	 */
	public function getDataForGetUnion()
	{
		return [
			[['[1, 3]'], '[1, 2]', '[2, 3]'],
			[['[1, 3]'], '[2, 3]', '[1, 2]'],

			[['[1, 4]'], '[1, 3]', '[2, 4]'],
			[['[1, 4]'], '[2, 4]', '[1, 2]'],

			[['[1, 3]'], '[1, 2)', '[2, 3]'],
			[['[1, 3]'], '[2, 3]', '[1, 2)'],

			[['[1, 2)', '(2, 3]'], '[1, 2)', '(2, 3]'],
			[['[1, 1]', '[2, 2]'], '[1, 1]', '[2, 2]'],

			[['[1, 2]', '(5, 5)'], '[1, 2]', '(5, 5)'],

			[['[1, 5)'], '[1, 5]', '(5, 5)'],
			[['[1, 5)'], '(5, 5)', '[1, 5]'],
		];
	}



	public function testWithLeftIsImmutable()
	{
		$a = Parser::parse('[1, 4]');
		$b = $a->withLeft(new IntegerBoundary(new Integer(0), Boundary::OPENED));
		Assert::notSame($a, $b);
		Assert::equal('[1, 4]', (string) $a);
		Assert::equal('(0, 4]', (string) $b);
	}



	public function testWithRightIsImmutable()
	{
		$a = Parser::parse('[1, 4]');
		$b = $a->withRight(new IntegerBoundary(new Integer(5), Boundary::OPENED));
		Assert::notSame($a, $b);
		Assert::equal('[1, 4]', (string) $a);
		Assert::equal('[1, 5)', (string) $b);
	}



	/**
	 * @param IntegerInterval $expected
	 * @param IntegerInterval $actual
	 */
	private function assertInterval(IntegerInterval $expected, IntegerInterval $actual)
	{
		Assert::equal((string) $expected, (string) $actual);
	}



	/**
	 * @param Interval[] $array
	 * @return string[]
	 */
	private function intervalArrayToString(array $array): array
	{
		return array_map(
			function (Interval $interval) {
				return (string) $interval;
			},
			$array
		);
	}

}



(new IntervalTest())->run();
