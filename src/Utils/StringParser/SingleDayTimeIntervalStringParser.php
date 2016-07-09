<?php

declare(strict_types = 1);

namespace Achse\Math\Interval\Utils\StringParser;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\SingleDayTimeBoundary;
use Achse\Math\Interval\Intervals\Interval;
use Achse\Math\Interval\Intervals\SingleDayTimeInterval;
use Achse\Math\Interval\Types\SingleDayTime;



class SingleDayTimeIntervalStringParser extends IntervalStringParser
{

	/**
	 * @param string $string
	 * @return SingleDayTimeInterval
	 */
	public static function parse(string $string) : Interval
	{
		list ($left, $right) = self::parseBoundariesStringsFromString($string);

		return new SingleDayTimeInterval(self::parseBoundary($left), self::parseBoundary($right));
	}



	/**
	 * @param string $string
	 * @return SingleDayTimeBoundary
	 */
	protected static function parseBoundary(string $string) : Boundary
	{
		list($elementString, $state) = self::parseBoundaryDataFromString($string);

		return new SingleDayTimeBoundary(SingleDayTime::from($elementString), $state);
	}

}
