<?php

declare(strict_types = 1);

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
	public static function create($leftElement, bool $leftState, $rightElement, bool $rightState) : DateTimeInterval
	{
		return new DateTimeInterval(
			DateTimeBoundaryFactory::create($leftElement, $leftState),
			DateTimeBoundaryFactory::create($rightElement, $rightState)
		);
	}

}
