<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalStringParser;



final class DateTimeImmutableIntervalStringParser extends IntervalStringParser
{

	/**
	 * @param string $string
	 * @return DateTimeImmutableInterval
	 */
	public static function parse(string $string): Interval
	{
		list ($left, $right) = self::parseBoundariesStringsFromString($string);

		return new DateTimeImmutableInterval(self::parseBoundary($left), self::parseBoundary($right));
	}



	/**
	 * @param string $string
	 * @return DateTimeImmutableBoundary
	 */
	protected static function parseBoundary(string $string): Boundary
	{
		list($elementString, $state) = self::parseBoundaryDataFromString($string);

		/** @var DateTimeImmutable $dateTime */
		$dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $elementString);

		return new DateTimeImmutableBoundary($dateTime, $state);
	}

}
