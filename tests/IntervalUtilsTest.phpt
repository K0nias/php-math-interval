<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval;

require_once __DIR__ . '/bootstrap.php';

use Achse\Math\Interval\Utils;
use Tester\Assert;
use Tester\TestCase;



final class IntervalUtilsTest extends TestCase
{

	/**
	 * @dataProvider getDataForNumberCmp
	 *
	 * @param int|float $expected
	 * @param int|float $first
	 * @param int|float $second
	 */
	public function testNumberCmp($expected, $first, $second)
	{
		Assert::equal($expected, Utils::numberCmp($first, $second));
	}



	/**
	 * @return array
	 */
	public function getDataForNumberCmp()
	{
		return [
			[0, 0, 0],
			[-1, 1, 2],
			[1, 2, 1],

			[1, 1.00000000000001, 1],
			[-1, 1, 1.00000000000001],

			// intentionally, this is beyond limit of precision
			[0, 1, 1.000000000000000000000000000000000000000000000000000000001],
		];
	}

}



(new IntervalUtilsTest())->run();
