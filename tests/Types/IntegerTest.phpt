<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

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
