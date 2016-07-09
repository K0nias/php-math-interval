<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval\Types;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Types\Integer;
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
