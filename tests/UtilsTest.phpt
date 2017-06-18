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
				$first = new DateTimeImmutable('2017-01-01T00:00:00+02:00');
				$second = new DateTimeImmutable('2017-01-01T00:00:00+00:00');

				Utils::isSameDate($first, $second);
			},
			InvalidArgumentException::class,
			'You cannot compare times from two different timezones (+02:00, +00:00) for same date.'
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
		return [
			[
				TRUE,
				new DateTimeImmutable('2017-01-01T00:00:00+02:00'),
				new DateTimeImmutable('2017-01-01T00:00:00+02:00'),
			],
			[
				TRUE,
				new DateTimeImmutable('2017-01-01T00:00:00+02:00'),
				new DateTimeImmutable('2017-01-01T23:59:59+02:00'),
			],
			[
				FALSE,
				new DateTimeImmutable('2017-01-01T00:00:00+02:00'),
				new DateTimeImmutable('2017-01-02T00:00:00+02:00'),
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
