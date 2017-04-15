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



class BoundaryTest extends TestCase
{

	public function testAll()
	{
		$boundary = new Boundary(new Integer(9), Boundary::CLOSED);
		Assert::equal('[9]', (string) $boundary);

		$boundary->setValue(new Integer(42));
		$boundary->setState(Boundary::OPENED);
		Assert::equal('(42)', (string) $boundary);
	}

}



(new BoundaryTest())->run();
