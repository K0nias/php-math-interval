<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval;

require __DIR__ . '/bootstrap.php';

use Achse\Math\Interval\Integer\Integer;
use Achse\Math\Interval\Integer\IntegerInterval;
use Achse\Math\Interval\IntervalRangesInvalidException;
use Achse\Math\Interval\Integer\IntegerIntervalStringParser as Parser;
use Tester\Assert;
use Tester\TestCase;



final class IntervalTest extends TestCase
{

	public function testErrors()
	{
		Assert::exception(
			function () {
				Parser::parse('[5, 0]');
			},
			IntervalRangesInvalidException::class,
			'Right endpoint cannot be less then Left endpoint.'
		);
	}



	public function testOpenedClosed()
	{
		Assert::true(Parser::parse('[1, 4]')->isClosed());
		Assert::false(Parser::parse('[1, 4]')->isOpened());

		Assert::false(Parser::parse('(1, 4)')->isClosed());
		Assert::true(Parser::parse('(1, 4)')->isOpened());

		Assert::false((Parser::parse('[1, 2)')->isOpened()));
		Assert::false((Parser::parse('[1, 2)')->isClosed()));

		Assert::false((Parser::parse('(1, 2]')->isOpened()));
		Assert::false((Parser::parse('(1, 2]')->isClosed()));
	}



	public function testIsDegenerate()
	{
		Assert::true((Parser::parse('[1, 1]')->isDegenerate()));

		Assert::false((Parser::parse('(1, 1)')->isDegenerate()));
		Assert::false((Parser::parse('(1, 1]')->isDegenerate()));

		Assert::false(Parser::parse('[1, 4]')->isDegenerate());
		Assert::false(Parser::parse('(1, 4)')->isDegenerate());
	}



	public function testIsProper()
	{
		Assert::false((Parser::parse('[1, 1]')->isProper()));
		Assert::false((Parser::parse('(1, 1)')->isProper()));
		Assert::false((Parser::parse('(1, 1]')->isProper()));

		Assert::true(Parser::parse('[1, 4]')->isProper());
		Assert::true(Parser::parse('(1, 4)')->isProper());
	}



	public function testIsEmpty()
	{
		Assert::true((Parser::parse('(1, 1)')->isEmpty()));
		Assert::true((Parser::parse('(1, 1]')->isEmpty()));
		Assert::false((Parser::parse('[1, 1]')->isEmpty()));

		Assert::false(Parser::parse('[1, 4]')->isEmpty());
		Assert::false(Parser::parse('(1, 4)')->isEmpty());
	}



	public function testIsContainingElement()
	{
		$interval = Parser::parse('[1, 2]');
		Assert::true($interval->isContainingElement(new Integer(1)));
		Assert::true($interval->isContainingElement(new Integer(2)));

		$interval = Parser::parse('(1, 2)');
		Assert::false($interval->isContainingElement(new Integer(1)));
		Assert::false($interval->isContainingElement(new Integer(2)));

		$interval = Parser::parse('[1, 3)');
		Assert::true($interval->isContainingElement(new Integer(1)));
		Assert::true($interval->isContainingElement(new Integer(2)));
		Assert::false($interval->isContainingElement(new Integer(3)));

		$interval = Parser::parse('(1, 3]');
		Assert::false($interval->isContainingElement(new Integer(1)));
		Assert::true($interval->isContainingElement(new Integer(2)));
		Assert::true($interval->isContainingElement(new Integer(3)));
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

		$intersection = Parser::parse('[1, 2]')->getIntersection(Parser::parse('[2, 3]'));
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		$intersection = Parser::parse('[2, 3]')->getIntersection(Parser::parse('[1, 2]'));
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		Assert::null(Parser::parse('[1, 2]')->getIntersection(Parser::parse('(2, 3)')));
		Assert::null(Parser::parse('(1, 2)')->getIntersection(Parser::parse('[2, 3]')));

		$intervalTwoThreeOpened = Parser::parse('(2, 3)');

		// (1, 3) ∩ (2, 4) ⟺ (2, 3)
		$intersection = Parser::parse('(1, 3)')->getIntersection(Parser::parse('(2, 4)'));
		$this->assertInterval($intervalTwoThreeOpened, $intersection);

		$intersection = Parser::parse('(2, 4)')->getIntersection(Parser::parse('(1, 3)'));
		$this->assertInterval($intervalTwoThreeOpened, $intersection);
	}



	public function testGetDifference()
	{
		// [1, 4] \ [0, 5]
		$diff = Parser::parse('[1, 4]')->getDifference(Parser::parse('[0, 5]'));
		Assert::count(0, $diff);

		// [1, 4] \ [1, 4]
		$diff = Parser::parse('[1, 4]')->getDifference(Parser::parse('[1, 4]'));
		Assert::count(0, $diff);

		// (1, 4) \ (1, 4)
		$diff = Parser::parse('(1, 4)')->getDifference(Parser::parse('(1, 4)'));
		Assert::count(0, $diff);

		// [1, 4] \ [2, 4]
		$diff = Parser::parse('[1, 4]')->getDifference(Parser::parse('[2, 4]'));
		Assert::count(1, $diff);
		Assert::equal('[1, 2)', (string) reset($diff));

		// [1, 4] \ [1, 2]
		$diff = Parser::parse('[1, 4]')->getDifference(Parser::parse('[1, 2]'));
		Assert::count(1, $diff);
		Assert::equal('(2, 4]', (string) reset($diff));

		// [1, 4] \ (2, 4)
		$diff = Parser::parse('[1, 4]')->getDifference(Parser::parse('(2, 4)'));
		Assert::count(2, $diff);
		Assert::equal('[1, 2]', (string) $diff[0]);
		Assert::equal('[4, 4]', (string) $diff[1]);

		// [1, 4] \ (1, 2)
		$diff = Parser::parse('[1, 4]')->getDifference(Parser::parse('(1, 2)'));
		Assert::count(2, $diff);
		Assert::equal('[1, 1]', (string) $diff[0]);
		Assert::equal('[2, 4]', (string) $diff[1]);

		// [1, 4] \ [2, 3]
		$diff = Parser::parse('[1, 4]')->getDifference(Parser::parse('[2, 3]'));
		Assert::count(2, $diff);
		Assert::equal('[1, 2)', (string) $diff[0]);
		Assert::equal('(3, 4]', (string) $diff[1]);

		// [1, 4] \ (2, 3)
		$diff = Parser::parse('[1, 4]')->getDifference(Parser::parse('(2, 3)'));
		Assert::count(2, $diff);
		Assert::equal('[1, 2]', (string) $diff[0]);
		Assert::equal('[3, 4]', (string) $diff[1]);
	}



	/**
	 * @param IntegerInterval $expected
	 * @param IntegerInterval $actual
	 */
	private function assertInterval(IntegerInterval $expected, IntegerInterval $actual)
	{
		Assert::equal((string) $expected, (string) $actual);
	}

}



(new IntervalTest())->run();
