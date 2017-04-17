<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\Integer;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Math\Interval\Integer\Integer;
use Achse\Tests\Interval\TestComparison;
use InvalidArgumentException;
use LogicException;
use Tester\Assert;
use Tester\TestCase;



final class IntegerTest extends TestCase
{

	use TestComparison;



	public function testAll()
	{
		$this->assertForComparison(new Integer(5), new Integer(6));
		Assert::exception(
			function () {
				(new Integer(5))->compare(new DateTimeImmutable());
			},
			LogicException::class,
			'You cannot compare sheep with the goat. Type Achse\Math\Interval\Integer\Integer expected,'
			. ' but Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable given.'
		);
	}



	/**
	 * @dataProvider getDataForFromString
	 *
	 * @param int $expected
	 * @param string $given
	 */
	public function testFromString(int $expected, string $given)
	{
		Assert::equal($expected, Integer::fromString($given)->toInt());
	}



	/**
	 * @return array
	 */
	public function getDataForFromString()
	{
		return [
			[1, '1'],
			[PHP_INT_MAX, (string) PHP_INT_MAX],
		];
	}



	/**
	 * @dataProvider getDataForFromStringFail
	 *
	 * @param string $given
	 */
	public function testFromStringFail(string $given)
	{
		Assert::exception(
			function () use ($given) {
				Integer::fromString($given);
			},
			InvalidArgumentException::class
		);
	}



	/**
	 * @return array
	 */
	public function getDataForFromStringFail()
	{
		return [
			[''],
			['100000000000000000000000000'],
			['1.1'],
			['Lorem Ipsum'],
		];
	}

}



(new IntegerTest())->run();
