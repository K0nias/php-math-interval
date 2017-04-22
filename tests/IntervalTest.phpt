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



	/**
	 * @dataProvider getDataForIsContaining
	 *
	 * @param bool $expected
	 * @param string $left
	 * @param string $right
	 */
	public function testIsContaining(bool $expected, string $left, string $right)
	{
		Assert::equal($expected, Parser::parse($left)->isContaining(Parser::parse($right)));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsContaining(): array
	{
		return [
			'[1, 4] contains (1, 4)' => [TRUE, '[1, 4]', '(1, 4)'],
			'(1, 4) NOT contains [1, 4]' => [FALSE, '(1, 4)', '[1, 4]'],

			'[1, 4] contains [2, 3]' => [TRUE, '[1, 4]', '[2, 3]'],
			'[2, 3] NOT contains [1, 4]' => [FALSE, '[2, 3]', '[1, 4]'],

			'[1, 4] contains (2, 3)' => [TRUE, '[1, 4]', '(2, 3)'],
			'(2, 3) NOT contains [1, 4]' => [FALSE, '(2, 3)', '[1, 4]'],

			'(1, 4) NOT contains [3, 4]' => [FALSE, '(1, 4)', '[3, 4]'],
			'(1, 4) NOT contains [1, 2]' => [FALSE, '(1, 4)', '[1, 2]'],

			[FALSE, '[1, 3]', '[3, 4]'],
			[FALSE, '[3, 4]', '[1, 3]'],
		];
	}



	/**
	 * @dataProvider getDataForIsOverlappedFromRightBy
	 *
	 * @param bool $expected
	 * @param string $left
	 * @param string $right
	 */
	public function testIsOverlappedFromRightBy(bool $expected, string $left, string $right)
	{
		Assert::equal($expected, Parser::parse($left)->isOverlappedFromRightBy(Parser::parse($right)));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataForIsOverlappedFromRightBy(): array
	{
		return [
			[TRUE, '[1, 2]', '[2, 3]'],
			[FALSE, '[2, 3]', '[1, 2]'],

			'(1, 2) ~ [2, 3]' => [FALSE, '(1, 2)', '[2, 3]'],
			'[1, 2] ~ (2, 3)' => [FALSE, '[1, 2]', '(2, 3)'],

			[TRUE, '(1, 3)', '(2, 4)'],
		];
	}



	/**
	 * @dataProvider getDataFomIsColliding
	 *
	 * @param bool $expected
	 * @param string $left
	 * @param string $right
	 */
	public function testIsColliding(bool $expected, string $left, string $right)
	{
		Assert::equal($expected, Parser::parse($left)->isColliding(Parser::parse($right)));
	}



	/**
	 * @return bool[][]|string[][]
	 */
	public function getDataFomIsColliding(): array
	{
		return [
			[TRUE, '[1, 2]', '[2, 3]'],
			[TRUE, '[2, 3]', '[1, 2]'],

			[FALSE, '[1, 2]', '(2, 3)'],
			[FALSE, '(1, 2)', '[2, 3]'],

			[TRUE, '(1, 3)', '(2, 4)'],
			[TRUE, '(2, 4)', '(1, 3)'],
		];
	}



	/**
	 * @dataProvider getDataForIntersectionTest
	 *
	 * @param string $expected
	 * @param string $left
	 * @param string $right
	 */
	public function testIntersection(string $expected = NULL, string $left, string $right)
	{
		$intersection = Parser::parse($left)->intersection(Parser::parse($right));
		if ($expected === NULL) {
			Assert::null($intersection);
		} else {
			Assert::equal($expected, (string) $intersection);
		}
	}



	/**
	 * @return null[][]|string[][]
	 */
	public function getDataForIntersectionTest(): array
	{
		return [
			['[2, 2]', '[1, 2]', '[2, 3]'],
			['[2, 2]', '[2, 3]', '[1, 2]'],

			[NULL, '[1, 2]', '(2, 3)'],
			[NULL, '(1, 2)', '[2, 3]'],

			'(1, 3) ∩ (2, 4) ⟺ (2, 3)' => ['(2, 3)', '(1, 3)', '(2, 4)'],
			['(2, 3)', '(2, 4)', '(1, 3)'],
		];
	}



	/**
	 * @dataProvider getDataForDifferenceTest
	 *
	 * @param string[] $expected
	 * @param string $left
	 * @param string $right
	 */
	public function testDifference(array $expected, string $left, string $right)
	{
		$difference = Parser::parse($left)->difference(Parser::parse($right));
		Assert::equal($expected, $this->intervalArrayToString($difference));
	}



	/**
	 * @return string[][][]|string[][]
	 */
	public function getDataForDifferenceTest(): array
	{
		return [
			'[1, 4] \ [0, 5]' => [[], '[1, 4]', '[0, 5]'],
			'[1, 4] \ [1, 4]' => [[], '[1, 4]', '[1, 4]'],
			'(1, 4) \ (1, 4)' => [[], '(1, 4)', '(1, 4)'],
			'[1, 4] \ [2, 4]' => [['[1, 2)'], '[1, 4]', '[2, 4]'],
			'[1, 4] \ [1, 2]' => [['(2, 4]'], '[1, 4]', '[1, 2]'],
			'[1, 4] \ (2, 4)' => [['[1, 2]', '[4, 4]'], '[1, 4]', '(2, 4)'],
			'[1, 4] \ (1, 2)' => [['[1, 1]', '[2, 4]'], '[1, 4]', '(1, 2)'],
			'[1, 4] \ [2, 3]' => [['[1, 2)', '(3, 4]'], '[1, 4]', '[2, 3]'],
			'[1, 4] \ (2, 3)' => [['[1, 2]', '[3, 4]'], '[1, 4]', '(2, 3)'],
		];
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
