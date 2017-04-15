<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval\Integer;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Integer\Integer;
use Achse\Tests\Interval\TestComparison;
use Tester\TestCase;



class IntegerTest extends TestCase
{

	use TestComparison;



	public function testAll()
	{
		$this->assertForComparison(new Integer(5), new Integer(6));
	}

}



(new IntegerTest())->run();
