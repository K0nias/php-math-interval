<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\DateTime;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\DateTime\DateTimeBoundaryFactory;
use Tester\Assert;
use Tester\TestCase;



final class DateTimeBoundaryFactoryTest extends TestCase
{

	public function testAll()
	{
		Assert::equal(
			'[2015-10-11 12:13:14]',
			(string) DateTimeBoundaryFactory::create(new \DateTime('2015-10-11 12:13:14'), Boundary::CLOSED)
		);
	}

}



(new DateTimeBoundaryFactoryTest())->run();
