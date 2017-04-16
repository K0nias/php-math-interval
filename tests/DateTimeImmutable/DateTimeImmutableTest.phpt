<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTimeImmutable;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Tests\Interval\TestComparison;
use Tester\TestCase;



final class DateTimeImmutableTest extends TestCase
{

	use TestComparison;



	public function testCompare()
	{
		$this->assertForComparison(
			new DateTimeImmutable('2015-05-05 12:00:00'),
			new DateTimeImmutable('2015-05-06 12:00:00')
		);
	}

}



(new DateTimeImmutableTest())->run();
