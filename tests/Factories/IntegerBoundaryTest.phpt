<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

require_once __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Factories\IntegerBoundaryFactory;
use Nette\InvalidArgumentException;
use Tester\Assert;
use Tester\TestCase;



class IntegerBoundaryFactoryTest extends TestCase
{

	public function testAll()
	{
		Assert::equal('[1]', (string) IntegerBoundaryFactory::create(1, Boundary::CLOSED));
		Assert::equal('(1)', (string) IntegerBoundaryFactory::create(1, Boundary::OPENED));

		Assert::exception(
			function () {
				IntegerBoundaryFactory::create(1.7, Boundary::OPENED);
			},
			InvalidArgumentException::class
		);

		Assert::equal('(' . PHP_INT_MAX . ')', (string) IntegerBoundaryFactory::create(PHP_INT_MAX, Boundary::OPENED));
	}

}



(new IntegerBoundaryFactoryTest())->run();
