<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

use Achse\Interval\Intervals\Interval;
use Achse\Interval\Types\Integer;
use Tester\Assert;
use Tester\TestCase;



class IntervalTest extends TestCase
{

	public function testIsContainingElement()
	{
		$one = new Integer(1);
		$two = new Integer(2);
		$three = new Integer(3);

		$interval = new Interval($one, Interval::CLOSED, $two, Interval::CLOSED);
		Assert::true($interval->isContainingElement($one));
		Assert::true($interval->isContainingElement($two));

		$interval = new Interval($one, Interval::OPENED, $two, Interval::OPENED);
		Assert::false($interval->isContainingElement($one));
		Assert::false($interval->isContainingElement($two));

		$interval = new Interval($one, Interval::CLOSED, $three, Interval::OPENED);
		Assert::true($interval->isContainingElement($one));
		Assert::true($interval->isContainingElement($two));
		Assert::false($interval->isContainingElement($three));

		$interval = new Interval($one, Interval::OPENED, $three, Interval::CLOSED);
		Assert::false($interval->isContainingElement($one));
		Assert::true($interval->isContainingElement($two));
		Assert::true($interval->isContainingElement($three));
	}

	public function testIsContaining() {
		$one = new Integer(1);
		$two = new Integer(2);
		$three = new Integer(3);
		$four = new Integer(4);

		$intervalOneFourClosed = new Interval($one, Interval::CLOSED, $four, Interval::CLOSED);
		$intervalOneFourOpened = new Interval($one, Interval::OPENED, $four, Interval::OPENED);

		$intervalTwoThreeClosed = new Interval($two, Interval::CLOSED, $three, Interval::CLOSED);
		$intervalTwoThreeOpened = new Interval($two, Interval::OPENED, $three, Interval::OPENED);

		// [1, 4] contains (1, 4)
		Assert::true($intervalOneFourClosed->isContaining($intervalOneFourOpened));
		// (1, 4) NOT contains [1, 4]
		Assert::false($intervalOneFourOpened->isContaining($intervalOneFourClosed));

		// [1, 4] contains [2, 3]
		Assert::true($intervalOneFourClosed->isContaining($intervalTwoThreeClosed));
		// [2, 3] NOT contains [1, 4]
		Assert::false($intervalTwoThreeClosed->isContaining($intervalOneFourClosed));

		// [1, 4] contains (2, 3)
		Assert::true($intervalOneFourClosed->isContaining($intervalTwoThreeOpened));
		// (2, 3) NOT contains [1, 4]
		Assert::false($intervalTwoThreeOpened->isContaining($intervalOneFourClosed));


		$left = new Interval($one, Interval::CLOSED, $three, Interval::CLOSED);
		$right = new Interval($three, Interval::CLOSED, $four, Interval::CLOSED);

		Assert::false($left->isContaining($right));
		Assert::false($right->isContaining($left));
	}

}



(new IntervalTest())->run();
