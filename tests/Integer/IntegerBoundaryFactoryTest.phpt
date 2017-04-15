<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\Integer;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Integer\IntegerBoundaryFactory;
use Tester\Assert;
use Tester\TestCase;



class IntegerBoundaryFactoryTest extends TestCase
{

	public function testAll()
	{
		Assert::equal('[1]', (string) IntegerBoundaryFactory::create(1, Boundary::CLOSED));
		Assert::equal('(1)', (string) IntegerBoundaryFactory::create(1, Boundary::OPENED));

		Assert::equal('(' . PHP_INT_MAX . ')', (string) IntegerBoundaryFactory::create(PHP_INT_MAX, Boundary::OPENED));
	}

}



(new IntegerBoundaryFactoryTest())->run();
