<?php

/**
 * @testCase
 */

declare(strict_types=1);

namespace Achse\Tests\Interval;

require_once __DIR__ . '/bootstrap.php';

use Achse\Math\Interval\Utils;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use Tester\Assert;
use Tester\TestCase;



final class UtilsTest extends TestCase
{

	public function testIsSameDateInvalidTimezone()
	{
		Assert::exception(
			function () {
				$first = new DateTimeImmutable('2017-01-01 00:00:00', new DateTimeZone('Europe/Prague'));
				$second = new DateTimeImmutable('2017-01-01 00:00:00', new DateTimeZone('UTC'));

				Utils::isSameDate($first, $second);
			},
			InvalidArgumentException::class,
			'You cannot compare times from two different timezones (Europe/Prague, UTC) for same date.'
			. ' Date is not always same at a time in different locations.'
		);
	}



	/**
	 * @dataProvider getDataForIsSameDate
	 *
	 * @param bool $expected
	 * @param DateTimeInterface $first
	 * @param DateTimeInterface $second
	 */
	public function testIsSameDate(bool $expected, DateTimeInterface $first, DateTimeInterface $second)
	{
		$message = sprintf(
			'[%s ?== %s] %s should have same date as %s',
			$first->format('Y-m-d'),
			$second->format('Y-m-d'),
			$first->format(DateTime::ATOM),
			$second->format(DateTime::ATOM)
		);

		Assert::equal($expected, Utils::isSameDate($first, $second), $message);
	}



	/**
	 * @return array
	 */
	public function getDataForIsSameDate()
	{
		$prague = new DateTimeZone('Europe/Prague');

		return [
			[
				TRUE,
				new DateTimeImmutable('2017-01-01 00:00:00', $prague),
				new DateTimeImmutable('2017-01-01 00:00:00', $prague),
			],
			[
				TRUE,
				new DateTimeImmutable('2017-01-01 00:00:00', $prague),
				new DateTimeImmutable('2017-01-01 23:59:59', $prague),
			],
			[
				FALSE,
				new DateTimeImmutable('2017-01-01 00:00:00', $prague),
				new DateTimeImmutable('2017-01-02 00:00:00', $prague),
			],
		];
	}



	/**
	 * @dataProvider getDataForNumberCmp
	 *
	 * @param int|float $expected
	 * @param int|float $first
	 * @param int|float $second
	 */
	public function testNumberCmp($expected, $first, $second)
	{
		Assert::equal($expected, Utils::numberCmp($first, $second));
	}



	/**
	 * @return array
	 */
	public function getDataForNumberCmp()
	{
		return [
			[0, 0, 0],
			[-1, 1, 2],
			[1, 2, 1],

			[1, 1.00000000000001, 1],
			[-1, 1, 1.00000000000001],

			// intentionally, this is beyond limit of precision
			[0, 1, 1.000000000000000000000000000000000000000000000000000000001],
		];
	}

}



(new UtilsTest())->run();
