<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

use Achse\Interval\Types\SingleDayTime;
use Nette\InvalidArgumentException;
use Tester\Assert;
use Tester\TestCase;



class SingleDayTimeTest extends TestCase
{

	public function testAdd()
	{
		$a = new SingleDayTime(1, 1, 1);
		$a->add(new SingleDayTime(1, 1, 1));
		Assert::equal('02:02:02', $a->format('H:i:s'));

		$a = new SingleDayTime(0, 0, 59);
		$a->add(new SingleDayTime(0, 0, 1));
		Assert::equal('00:01:00', $a->format('H:i:s'));

		$a = new SingleDayTime(0, 59, 59);
		$a->add(new SingleDayTime(0, 0, 1));
		Assert::equal('01:00:00', $a->format('H:i:s'));

		$a = new SingleDayTime(0, 59, 59);
		$a->add(new SingleDayTime(0, 0, 2));
		Assert::equal('01:00:01', $a->format('H:i:s'));

		$a = new SingleDayTime(0, 59, 59);
		$a->add(new SingleDayTime(0, 1, 2));
		Assert::equal('01:01:01', $a->format('H:i:s'));
	}



	public function testInvalidAdd()
	{
		Assert::exception(
			function () {
				(new SingleDayTime(23, 59, 59))->add(new SingleDayTime(0, 0, 1));
			},
			InvalidArgumentException::class
		);
	}



	public function testSub()
	{
		$a = new SingleDayTime(1, 1, 1);
		$a->sub(new SingleDayTime(1, 1, 1));
		Assert::equal('00:00:00', $a->format('H:i:s'));

		$a = new SingleDayTime(10, 10, 10);
		$a->sub(new SingleDayTime(0, 0, 40));
		Assert::equal('10:09:30', $a->format('H:i:s'));

		$a = new SingleDayTime(10, 10, 5);
		$a->sub(new SingleDayTime(0, 12, 40));
		Assert::equal('09:57:25', $a->format('H:i:s'));

		$a = new SingleDayTime(1, 0, 0);
		$a->sub(new SingleDayTime(0, 0, 1));
		Assert::equal('00:59:59', $a->format('H:i:s'));
	}



	public function testInvalidSub()
	{
		Assert::exception(
			function () {
				(new SingleDayTime(0, 0, 0))->sub(new SingleDayTime(0, 0, 1));
			},
			InvalidArgumentException::class
		);

		Assert::exception(
			function () {
				(new SingleDayTime(23, 59, 58))->sub(new SingleDayTime(23, 59, 59));
			},
			InvalidArgumentException::class
		);
	}

}



(new SingleDayTimeTest())->run();
