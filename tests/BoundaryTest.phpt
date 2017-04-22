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
	 * @dataProvider getDataForAsOpened
	 *
	 * @param Boundary $boundary
	 */
	public function testAsOpened(Boundary $boundary)
	{
		$openedBoundary = $boundary->asOpened();
		Assert::notSame($boundary, $openedBoundary);
		Assert::true($openedBoundary->isOpened());
	}



	/**
	 * @return Boundary[][]
	 */
	public function getDataForAsOpened(): array
	{
		return [
			[new Boundary(new Integer(1), Boundary::OPENED)],
			[new Boundary(new Integer(1), Boundary::CLOSED)],
		];
	}



	/**
	 * @dataProvider getDataForAsClosed
	 *
	 * @param Boundary $boundary
	 */
	public function testAsClosed(Boundary $boundary)
	{
		$closedBoundary = $boundary->asClosed();
		Assert::notSame($boundary, $closedBoundary);
		Assert::true($closedBoundary->isClosed());
	}



	/**
	 * @return Boundary[]
	 */
	public function getDataForAsClosed(): array
	{
		return [
			[new Boundary(new Integer(1), Boundary::OPENED)],
			[new Boundary(new Integer(1), Boundary::CLOSED)],
		];
	}



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
