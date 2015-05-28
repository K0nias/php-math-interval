<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

use Achse\Interval\Types\DateTime;
use Tester\Assert;
use Tester\TestCase;



class DateTimeTest extends TestCase
{

	public function testCompare()
	{
		$dateTimeFive = new DateTime('2015-05-05 12:00:00');
		$dateTimeSix = new DateTime('2015-05-06 12:00:00');

		Assert::true($dateTimeFive->lessThen($dateTimeSix));
		Assert::true($dateTimeFive->lessThenOrEqual($dateTimeSix));

		Assert::false($dateTimeSix->lessThen($dateTimeFive));
		Assert::false($dateTimeSix->lessThenOrEqual($dateTimeFive));

		Assert::false($dateTimeFive->equal($dateTimeSix));
		Assert::false($dateTimeSix->equal($dateTimeFive));
	}

}



(new DateTimeTest())->run();
