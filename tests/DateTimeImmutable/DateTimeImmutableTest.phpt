<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTimeImmutable;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Math\Interval\Integer\Integer;
use Achse\Tests\Interval\TestComparison;
use LogicException;
use Tester\Assert;
use Tester\TestCase;



final class DateTimeImmutableTest extends TestCase
{

	use TestComparison;



	public function testCompare()
	{
		$this->assertForComparison(
			new DateTimeImmutable('2015-05-05T12:00:00+02:00'),
			new DateTimeImmutable('2015-05-06T12:00:00+02:00')
		);
		Assert::exception(
			function () {
				(new DateTimeImmutable())->compare(new Integer(5));
			},
			LogicException::class,
			'Value must be type of Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable'
			. ' but Achse\Math\Interval\Integer\Integer given.'
		);
	}

}



(new DateTimeImmutableTest())->run();
