<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

use Achse\Interval\Types\DateTime;
use Achse\Interval\Types\SingleDayTime;
use Nette\InvalidArgumentException;
use Tester\Assert;
use Tester\TestCase;



class SingleDayTimeTest extends TestCase
{

	public function testToDateTime()
	{
		$day = new DateTime('2015-12-24 00:00:00');

		Assert::equal(new DateTime('2015-12-24 12:13:14'), (new SingleDayTime(12, 13, 14))->toDateTime($day));
	}



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



	public function testModify()
	{
		$time = new SingleDayTime(10, 10, 10);

		$time->modify('+1 hour');
		Assert::equal('11:10:10', $time->format('H:i:s'));

		$time->modify('+1 minute');
		Assert::equal('11:11:10', $time->format('H:i:s'));

		$time->modify('+1 second');
		Assert::equal('11:11:11', $time->format('H:i:s'));

		$time->modify('-80 seconds');
		Assert::equal('11:09:51', $time->format('H:i:s'));

		Assert::exception(
			function () {
				(new SingleDayTime(5, 14, 58))->modify('-6 hours');
			},
			InvalidArgumentException::class
		);
	}



	public function testFromDateTime()
	{
		$time = SingleDayTime::fromDateTime(new DateTime('2015-05-14 09:10:11'));
		Assert::equal('09:10:11', $time->format('H:i:s'));
	}



	public function testFrom()
	{
		$time = SingleDayTime::from('2015-05-15 1:2:3');
		Assert::equal('01:02:03', $time->format('H:i:s'));

		$time = SingleDayTime::from('July 1, 2001 20:21:22');
		Assert::equal('20:21:22', $time->format('H:i:s'));

		$time = SingleDayTime::from(new SingleDayTime(4, 17, 39));
		Assert::equal('04:17:39', $time->format('H:i:s'));
	}

}



(new SingleDayTimeTest())->run();
