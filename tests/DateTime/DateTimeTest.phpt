<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTime;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTime\DateTime;
use Achse\Tests\Interval\TestComparison;
use Tester\TestCase;



class DateTimeTest extends TestCase
{

	use TestComparison;



	public function testCompare()
	{
		$this->assertForComparison(new DateTime('2015-05-05 12:00:00'), new DateTime('2015-05-06 12:00:00'));
	}

}



(new DateTimeTest())->run();
