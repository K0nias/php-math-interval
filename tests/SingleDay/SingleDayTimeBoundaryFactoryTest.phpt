<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\SingleDay;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\SingleDay\SingleDayTimeBoundaryFactory;
use Tester\Assert;
use Tester\TestCase;



final class SingleDayTimeBoundaryFactoryTest extends TestCase
{

	/**
	 * @dataProvider getDataForAllTest
	 *
	 * @param string $expected
	 * @param string $from
	 */
	public function testAll(string $expected, string $from): void
	{
		Assert::equal($expected, (string) SingleDayTimeBoundaryFactory::create($from, Boundary::CLOSED));
	}



	/**
	 * @return string[][]
	 */
	public function getDataForAllTest()
	{
		return [
			['[12:13:14]', '12:13:14'],
			['[01:02:03]', '01:02:03'],
		];
	}

}



(new SingleDayTimeBoundaryFactoryTest())->run();
