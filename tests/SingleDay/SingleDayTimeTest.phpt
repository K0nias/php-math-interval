<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\SingleDay;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Math\Interval\ModificationNotPossibleException;
use Achse\Math\Interval\SingleDay\SingleDayTime;
use Achse\Tests\Interval\TestComparison;
use InvalidArgumentException;
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
		$day = new DateTimeImmutable('2015-12-24 00:00:00');

		Assert::equal('2015-12-24 12:13:14', (string) (new SingleDayTime(12, 13, 14))->toDateTime($day));
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
			ModificationNotPossibleException::class
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
			ModificationNotPossibleException::class
		);

		Assert::exception(
			function () {
				(new SingleDayTime(23, 59, 58))->sub(new SingleDayTime(23, 59, 59));
			},
			ModificationNotPossibleException::class
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
			ModificationNotPossibleException::class
		);
	}



	public function testFromDateTime()
	{
		$time = SingleDayTime::fromDateTime(new \DateTimeImmutable('2015-05-14 09:10:11'));
		Assert::equal('09:10:11', $time->format('H:i:s'));
	}



	public function testFrom()
	{
		$time = SingleDayTime::from('01:02:03');
		Assert::equal('01:02:03', $time->format('H:i:s'));

		$time = SingleDayTime::from(new SingleDayTime(4, 17, 39));
		Assert::equal('04:17:39', $time->format('H:i:s'));
	}



	/**
	 * @dataProvider getDataForFromFail
	 * @param string $from
	 */
	public function testFromFail(string $from)
	{
		Assert::exception(
			function () use ($from) {
				SingleDayTime::from($from);
			},
			InvalidArgumentException::class
		);
	}



	/**
	 * @return array
	 */
	public function getDataForFromFail()
	{
		return [
			[''],
			['1:2:3'],
			['100:200:300'],
			['99:99:00'],
		];
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
	public function getDataForFormat(): array
	{
		return [
			['18:19:20', '18:19:20', 'H:i:s'],
			[
				'pm, PM, 763, 6, 18, 06, 18, 19, 20, 000000',
				'18:19:20',
				'a, A, B, g, G, h, H, i, s, u'
			],
			['Y-m-d 18:19:20', '18:19:20', '\Y-\m-\d H:i:s'],
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
			LogicException::class
		);
	}



	/**
	 * @return array
	 */
	public function getDataForFormatFail(): array
	{
		return array_map(
			function ($symbol) {
				return ['10:11:12', $symbol];
			},
			[
				// day
				'd',
				'D',
				'j',
				'l',
				'N',
				'S',
				'w',
				'z',
				// Week
				'W',
				// Month
				'F',
				'm',
				'M',
				'n',
				't',
				// Year
				'L',
				'o',
				'Y',
				'y',
				// Timezone
				'e',
				'I',
				'O',
				'P',
				'T',
				'Z',
				// Full DateTime
				'c',
				'r',
				'U',
			]
		);
	}

}



(new SingleDayTimeTest())->run();
