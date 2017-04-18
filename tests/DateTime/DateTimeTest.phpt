<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTime;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTime\DateTime;
use Achse\Math\Interval\Integer\Integer;
use Achse\Tests\Interval\TestComparison;
use LogicException;
use Tester\Assert;
use Tester\TestCase;



final class DateTimeTest extends TestCase
{

	use TestComparison;



	public function testCompare()
	{
		$this->assertForComparison(new DateTime('2015-05-05 12:00:00'), new DateTime('2015-05-06 12:00:00'));
		Assert::exception(
			function () {
				(new DateTime())->compare(new Integer(5));
			},
			LogicException::class,
			'Value must be type of Achse\Math\Interval\DateTime\DateTime'
			. ' but Achse\Math\Interval\Integer\Integer given.'
		);
	}

}



(new DateTimeTest())->run();
