<?php

namespace Achse\Math\Interval\Utils\StringParser;

use Achse\Math\Interval\Boundaries\IntegerBoundary;
use Achse\Math\Interval\Intervals\IntegerInterval;
use Achse\Math\Interval\Types\Integer;



class IntegerIntervalStringParser extends IntervalStringParser
{

	/**
	 * @param string $string
	 * @return IntegerInterval
	 */
	public static function parse($string)
	{
		list ($left, $right) = self::parseBoundariesStringsFromString($string);

		return new IntegerInterval(self::parseBoundary($left), self::parseBoundary($right));
	}



	/**
	 * @param string $string
	 * @return IntegerBoundary
	 */
	protected static function parseBoundary($string)
	{
		list($elementString, $state) = self::parseBoundaryDataFromString($string);

		return new IntegerBoundary(Integer::fromString($elementString), $state);
	}
}
