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

}



(new IntervalTest())->run();
