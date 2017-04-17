<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval;

require_once __DIR__ . '/bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Integer\Integer;
use Tester\Assert;
use Tester\TestCase;



final class BoundaryTest extends TestCase
{

	use TestComparison;



	/**
	 * @dataProvider getDataForComparison
	 *
	 * @param Boundary $smaller
	 * @param Boundary $grater
	 */
	public function testComparison(Boundary $smaller, Boundary $grater)
	{
		$this->assertForComparison($smaller, $grater);
	}



	/**
	 * @return array
	 */
	public function getDataForComparison()
	{
		return [
			[
				new Boundary(new Integer(1), Boundary::OPENED),
				new Boundary(new Integer(1), Boundary::CLOSED),
			],
			[
				new Boundary(new Integer(1), Boundary::OPENED),
				new Boundary(new Integer(2), Boundary::OPENED),
			],
			[
				new Boundary(new Integer(1), Boundary::CLOSED),
				new Boundary(new Integer(2), Boundary::CLOSED),
			],
			[
				new Boundary(new Integer(2), Boundary::CLOSED),
				new Boundary(new Integer(3), Boundary::OPENED),
			],
		];
	}



	public function testConstruct()
	{
		$boundary = new Boundary(new Integer(9), Boundary::CLOSED);
		Assert::equal('[9]', (string) $boundary);
	}

}



(new BoundaryTest())->run();
