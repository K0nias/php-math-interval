<?php

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Intervals\DateTimeInterval;
use Nette\Object;



class DateTimeIntervalFactory extends Object
{

	/**
	 * @param \DateTime|string|int $leftElement
	 * @param bool $leftState
	 * @param \DateTime|string|int $rightElement
	 * @param bool $rightState
	 * @return DateTimeInterval
	 */
	public static function create($leftElement, $leftState, $rightElement, $rightState)
	{
		return new DateTimeInterval(
			DateTimeBoundaryFactory::create($leftElement, $leftState),
			DateTimeBoundaryFactory::create($rightElement, $rightState)
		);
	}

}
