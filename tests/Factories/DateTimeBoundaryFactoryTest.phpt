<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval\Types;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\DateTimeBoundaryFactory;
use Tester\Assert;
use Tester\TestCase;



class DateTimeBoundaryFactoryTest extends TestCase
{

	public function testAll()
	{
		Assert::equal(
			'[2015-10-11 12:13:14]',
			(string) DateTimeBoundaryFactory::create('2015-10-11 12:13:14', Boundary::CLOSED)
		);
	}

}



(new DateTimeBoundaryFactoryTest())->run();
