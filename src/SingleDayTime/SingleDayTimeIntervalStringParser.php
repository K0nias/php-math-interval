<?php

declare(strict_types=1);

namespace Achse\Math\Interval\SingleDayTime;

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalStringParser;



final class SingleDayTimeIntervalStringParser extends IntervalStringParser
{

	/**
	 * @param string $string
	 * @return SingleDayTimeInterval
	 */
	public static function parse(string $string): Interval
	{
		list ($left, $right) = self::parseBoundariesStringsFromString($string);

		return new SingleDayTimeInterval(self::parseBoundary($left), self::parseBoundary($right));
	}



	/**
	 * @param string $string
	 * @return SingleDayTimeBoundary
	 */
	protected static function parseBoundary(string $string): Boundary
	{
		list($elementString, $state) = self::parseBoundaryDataFromString($string);

		return new SingleDayTimeBoundary(SingleDayTime::from($elementString), $state);
	}

}
