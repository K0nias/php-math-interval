<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTimeImmutable;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutableBoundaryFactory;
use Tester\Assert;
use Tester\TestCase;



final class DateTimeImmutableBoundaryFactoryTest extends TestCase
{

	public function testAll()
	{
		Assert::equal(
			'[2015-10-11T12:13:14+02:00]',
			(string) DateTimeImmutableBoundaryFactory::create(
				new \DateTimeImmutable('2015-10-11T12:13:14+02:00'),
				Boundary::CLOSED
			)
		);
	}

}



(new DateTimeImmutableBoundaryFactoryTest())->run();
