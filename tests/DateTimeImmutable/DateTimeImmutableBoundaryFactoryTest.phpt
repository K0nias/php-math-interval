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
			'[2015-10-11 12:13:14]',
			(string) DateTimeImmutableBoundaryFactory::create('2015-10-11 12:13:14', Boundary::CLOSED)
		);
	}

}



(new DateTimeImmutableBoundaryFactoryTest())->run();
