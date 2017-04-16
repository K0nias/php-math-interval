<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval\SingleDay;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTime\DateTime;
use Achse\Math\Interval\ModificationNotPossibleException;
use Achse\Math\Interval\SingleDay\SingleDayTime;
use Achse\Tests\Interval\TestComparison;
use LogicException;
use Tester\Assert;
use Tester\TestCase;



final class SingleDayTimeTest extends TestCase
{

	use TestComparison;



	public function testCompare()
	{
		$this->assertForComparison(new SingleDayTime(0, 5, 0), new SingleDayTime(0, 6, 0));
	}



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
			ModificationNotPossibleException::final class
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
			ModificationNotPossibleException::final class
		);

		Assert::exception(
			function () {
				(new SingleDayTime(23, 59, 58))->sub(new SingleDayTime(23, 59, 59));
			},
			ModificationNotPossibleException::final class
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
			ModificationNotPossibleException::final class
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



	/**
	 * @dataProvider getDataForFormat
	 *
	 * @param string $expected
	 * @param string $dayTime
	 * @param string $format
	 */
	public function testFormat(string $expected, string $dayTime, string $format)
	{
		$time = SingleDayTime::from($dayTime);
		Assert::equal($expected, $time->format($format));
	}



	/**
	 * @return array
	 */
	public function getDataForFormat() : array
	{
		return [
			['', '18:19:20', ''],
			['18:19:20', '18:19:20', 'H:i:s'],
			['18:19:20', '2015-12-24 18:19:20', 'H:i:s'],

			[
				'pm, PM, 763, 6, 18, 06, 18, 19, 20, 000000',
				'2015-12-24 18:19:20',
				'a, A, B, g, G, h, H, i, s, u'
			],

			['Y-m-d 18:19:20', '2015-12-24 18:19:20', '\Y-\m-\d H:i:s'],
		];
	}



	/**
	 * @dataProvider getDataForFormatFail
	 *
	 * @param string $dayTime
	 * @param string $format
	 */
	public function testFormatFail(string $dayTime, string $format)
	{
		$time = SingleDayTime::from($dayTime);
		Assert::exception(
			function () use ($time, $format) {
				$time->format($format);
			},
			LogicException::final class
		);
	}



	/**
	 * @return array
	 */
	public function getDataForFormatFail() : array
	{
		return array_map(
			function ($symbol) {
				return ['2015-12-24 10:11:12', $symbol];
			},
			SingleDayTime::NOT_ALLOWED_FORMAT_SYMBOLS
		);
	}

}



(new SingleDayTimeTest())->run();
