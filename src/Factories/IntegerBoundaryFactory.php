<?php

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\IntegerBoundary;
use Achse\Math\Interval\IntervalParseErrorException;
use Achse\Math\Interval\Intervals\IntegerInterval;
use Achse\Math\Interval\Intervals\Interval;
use Achse\Math\Interval\Types\DateTime;
use Achse\Math\Interval\Types\Integer;
use Achse\Math\Interval\Types\SingleDayTime;
use Achse\Math\Interval\Utils\IntegerIntervalStringParser;
use Nette\Object;
use Nette\Utils\Strings;



class IntegerBoundaryFactory extends Object
{

	/**
	 * @param int $value
	 * @param bool $state
	 * @return Boundary
	 */
	public static function create($value, $state)
	{
		$value = new Integer($value);

		return new Boundary($value, $state);
	}

}
