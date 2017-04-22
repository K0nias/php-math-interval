<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval\SingleDayTime;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\DateTime\DateTime;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Math\Interval\ModificationNotPossibleException;
use Achse\Math\Interval\SingleDayTime\SingleDayTime;
use Achse\Tests\Interval\TestComparison;
use InvalidArgumentException;
use LogicException;
use stdClass;
use Tester\Assert;
use Tester\TestCase;



final class SingleDayTimeTest extends TestCase
{

	use TestComparison;



	public function testWithXy()
	{
		$interval = new SingleDayTime(0, 0, 0);
		Assert::equal('01:00:00', (string) $interval->withHours(1));
		Assert::equal('00:01:00', (string) $interval->withMinutes(1));
		Assert::equal('00:00:01', (string) $interval->withSeconds(1));
	}



	/**
	 * @dataProvider getDataForConstructorValidity
	 *
	 * @param string $expectedErrorMessage
	 * @param int $hours
	 * @param int $minutes
	 * @param float $seconds
	 */
	public function testConstructorValidity(string $expectedErrorMessage, int $hours, int $minutes, float $seconds)
	{
		Assert::exception(
			function () use ($hours, $minutes, $seconds) {
				new SingleDayTime($hours, $minutes, $seconds);
			},
			InvalidArgumentException::class,
			$expectedErrorMessage
		);
	}



	/**
	 * @return int[][]|float[][]
	 */
	public function getDataForConstructorValidity(): array
	{
		return [
			['Hours have to be 0-23.', -1, 0, 0],
			['Minutes have to be 0-59.', 0, -1, 0],
			['Seconds have to be 0-59.', 0, 0, -1],

			['Hours have to be 0-23.', 60, 0, 0],
			['Minutes have to be 0-59.', 0, 60, 0],
			['Seconds have to be 0-59.', 0, 0, 60],
		];
	}



	public function testCompare()
	{
		$this->assertForComparison(new SingleDayTime(0, 5, 0), new SingleDayTime(0, 6, 0));
		Assert::exception(
			function () {
				(new SingleDayTime(2, 3, 5))->compare(new DateTimeImmutable());
			},
			LogicException::class,
			'Value must be type of Achse\Math\Interval\SingleDayTime\SingleDayTime'
			. ' but Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable given.'
		);
	}



	public function testToDateTime()
	{
		$day = new DateTimeImmutable('2015-12-24 00:00:00');

		Assert::equal('2015-12-24 12:13:14', (string) (new SingleDayTime(12, 13, 14))->toDateTime($day));
	}



	/**
	 * @dataProvider getDataForAddTest
	 *
	 * @param string $expected
	 * @param SingleDayTime $first
	 * @param SingleDayTime $second
	 */
	public function testAdd(string $expected, SingleDayTime $first, SingleDayTime $second)
	{
		$result = $first->add($second);
		Assert::equal($expected, $result->format('H:i:s'));
	}



	/**
	 * @return string[][]|SingleDayTime[][]
	 */
	public function getDataForAddTest(): array
	{
		return [
			['02:02:02', new SingleDayTime(1, 1, 1), new SingleDayTime(1, 1, 1)],
			['00:01:00', new SingleDayTime(0, 0, 59), new SingleDayTime(0, 0, 1)],
			['01:00:00', new SingleDayTime(0, 59, 59), new SingleDayTime(0, 0, 1)],
			['01:00:01', new SingleDayTime(0, 59, 59), new SingleDayTime(0, 0, 2)],
			['01:01:01', new SingleDayTime(0, 59, 59), new SingleDayTime(0, 1, 2)],
		];
	}



	public function testAddDoesNotModify()
	{
		$interval = new SingleDayTime(1, 1, 1);
		$newInterval = $interval->add(new SingleDayTime(1, 1, 1));
		Assert::notSame($interval, $newInterval);
		Assert::equal('01:01:01', (string) $interval);
		Assert::equal('02:02:02', (string) $newInterval);
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



	/**
	 * @dataProvider getDataForSubTest
	 *
	 * @param string $expected
	 * @param SingleDayTime $first
	 * @param SingleDayTime $second
	 */
	public function testSub(string $expected, SingleDayTime $first, SingleDayTime $second)
	{
		$result = $first->sub($second);
		Assert::equal($expected, $result->format('H:i:s'));
	}



	/**
	 * @return string[][]|SingleDayTime[][]
	 */
	public function getDataForSubTest(): array
	{
		return [
			['00:00:00', new SingleDayTime(1, 1, 1), new SingleDayTime(1, 1, 1)],
			['10:09:30', new SingleDayTime(10, 10, 10), new SingleDayTime(0, 0, 40)],
			['09:57:25', new SingleDayTime(10, 10, 5), new SingleDayTime(0, 12, 40)],
			['00:59:59', new SingleDayTime(1, 0, 0), new SingleDayTime(0, 0, 1)],
		];
	}



	public function testSubDoesNotModify()
	{
		$interval = new SingleDayTime(1, 1, 1);
		$newInterval = $interval->sub(new SingleDayTime(1, 1, 1));
		Assert::notSame($interval, $newInterval);
		Assert::equal('01:01:01', (string) $interval);
		Assert::equal('00:00:00', (string) $newInterval);
	}



	/**
	 * @dataProvider getDataForInvalidSubTest
	 *
	 * @param SingleDayTime $first
	 * @param SingleDayTime $second
	 */
	public function testInvalidSub(SingleDayTime $first, SingleDayTime $second)
	{
		Assert::exception(
			function () use ($first, $second) {
				$first->sub($second);
			},
			ModificationNotPossibleException::class
		);
	}



	/**
	 * @return SingleDayTime[][]
	 */
	public function getDataForInvalidSubTest(): array
	{
		return [
			[new SingleDayTime(0, 0, 0), new SingleDayTime(0, 0, 1)],
			[new SingleDayTime(23, 59, 58), new SingleDayTime(23, 59, 59)],
		];
	}



	/**
	 * @dataProvider getDataForModifyTest
	 *
	 * @param string $expected
	 * @param string $given
	 */
	public function testModify(string $expected, string $given)
	{
		$time = new SingleDayTime(10, 10, 10);

		$time = $time->modify($given);
		Assert::equal($expected, $time->format('H:i:s'));
	}



	/**
	 * @return string[][]
	 */
	public function getDataForModifyTest(): array
	{
		return [
			['11:10:10', '+1 hour'],
			['10:11:10', '+1 minute'],
			['10:10:11', '+1 second'],
			['10:08:50', '-80 seconds'],
		];
	}



	public function testModifyInvalidInput()
	{
		Assert::exception(
			function () {
				(new SingleDayTime(5, 14, 58))->modify('-6 hours');
			},
			ModificationNotPossibleException::class
		);
	}



	public function testModifyIsImmutable()
	{
		$time = new SingleDayTime(10, 10, 10);
		$modifiedTime = $time->modify('+1 hour');
		Assert::notSame($time, $modifiedTime);
		Assert::equal('10:10:10', (string) $time);
		Assert::equal('11:10:10', (string) $modifiedTime);
	}



	public function testFromDateTime()
	{
		$time = SingleDayTime::fromDateTime(new \DateTimeImmutable('2015-05-14 09:10:11'));
		Assert::equal('09:10:11', $time->format('H:i:s'));
	}



	/**
	 * @dataProvider getDataForFromTest
	 *
	 * @param string $expected
	 * @param string|\DateTimeInterface|SingleDayTime $input
	 */
	public function testFrom(string $expected, $input)
	{
		$time = SingleDayTime::from($input);
		Assert::equal($expected, $time->format('H:i:s'));
	}



	/**
	 * @return string[][]
	 */
	public function getDataForFromTest()
	{
		return [
			['01:02:03', '01:02:03'],
			['04:17:39', new SingleDayTime(4, 17, 39)],

			['04:17:39', new \DateTime('2017-04-18 04:17:39')],
			['04:17:39', new \DateTimeImmutable('2017-04-18 04:17:39')],

			['04:17:39', new DateTime('2017-04-18 04:17:39')],
			['04:17:39', new DateTimeImmutable('2017-04-18 04:17:39')],
		];
	}



	/**
	 * @dataProvider getDataForFromFail
	 *
	 * @param string $expectedErrorMessage
	 * @param mixed $from
	 */
	public function testFromFail(string $expectedErrorMessage, $from)
	{
		Assert::exception(
			function () use ($from) {
				SingleDayTime::from($from);
			},
			InvalidArgumentException::class,
			$expectedErrorMessage
		);
	}



	/**
	 * @return array
	 */
	public function getDataForFromFail()
	{
		return [
			['Given string  not valid H:i:s time.', ''],
			['Given string 1:2:3 not valid H:i:s time.', '1:2:3'],
			['Given string 100:200:300 not valid H:i:s time.', '100:200:300'],
			['Given string 99:99:00 not valid H:i:s time.', '99:99:00'],
			['Argument is not type of DateTimeInterface or SingleDayTime or string. Type: NULL given.', NULL],
			['Argument is not type of DateTimeInterface or SingleDayTime or string. Type: integer given.', 123456],
			[
				'Argument is not type of DateTimeInterface or SingleDayTime or string. Type: stdClass given.',
				new stdClass(),
			],
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
