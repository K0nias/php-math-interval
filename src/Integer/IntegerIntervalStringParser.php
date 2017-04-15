<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Integer;

use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalStringParser;



class IntegerIntervalStringParser extends IntervalStringParser
{

	/**
	 * @param string $string
	 * @return IntegerInterval
	 */
	public static function parse(string $string): Interval
	{
		list ($left, $right) = self::parseBoundariesStringsFromString($string);

		return new IntegerInterval(self::parseBoundary($left), self::parseBoundary($right));
	}



	/**
	 * @param string $string
	 * @return IntegerBoundary
	 */
	protected static function parseBoundary(string $string): Boundary
	{
		list($elementString, $state) = self::parseBoundaryDataFromString($string);

		return new IntegerBoundary(Integer::fromString($elementString), $state);
	}
}
