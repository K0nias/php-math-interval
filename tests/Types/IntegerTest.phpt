<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

use Achse\Interval\Types\Integer;
use Tester\Assert;
use Tester\TestCase;



class IntegerTest extends TestCase
{

	public function testAll()
	{
		$five = new Integer(5);
		$six = new Integer(6);

		Assert::equal(5, $five->toInt());
		Assert::equal(6, $six->toInt());

		Assert::true($five->lessThen($six));
		Assert::true($five->lessThenOrEqual($six));

		Assert::false($six->lessThen($five));
		Assert::false($six->lessThenOrEqual($five));

		Assert::false($five->equal($six));
		Assert::false($six->equal($five));
	}

}



(new IntegerTest())->run();
