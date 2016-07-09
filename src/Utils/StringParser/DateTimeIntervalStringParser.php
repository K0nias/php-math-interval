<?php

declare(strict_types = 1);

namespace Achse\Math\Interval\Utils\StringParser;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\DateTimeBoundary;
use Achse\Math\Interval\Intervals\DateTimeInterval;
use Achse\Math\Interval\Intervals\Interval;
use Achse\Math\Interval\Types\DateTime;



class DateTimeIntervalStringParser extends IntervalStringParser
{

	/**
	 * @param string $string
	 * @return DateTimeInterval
	 */
	public static function parse(string $string) : Interval
	{
		list ($left, $right) = self::parseBoundariesStringsFromString($string);

		return new DateTimeInterval(self::parseBoundary($left), self::parseBoundary($right));
	}



	/**
	 * @param string $string
	 * @return DateTimeBoundary
	 */
	protected static function parseBoundary(string $string) : Boundary
	{
		list($elementString, $state) = self::parseBoundaryDataFromString($string);

		/** @var DateTime $dateTime */
		$dateTime = DateTime::from($elementString);

		return new DateTimeBoundary($dateTime, $state);
	}

}
