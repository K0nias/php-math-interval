<?php

declare(strict_types = 1);

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Intervals\SingleDayTimeInterval;
use Nette\Object;



class SingleDayTimeIntervalFactory extends Object
{

	/**
	 * @param \DateTime|string|int $leftElement
	 * @param bool $leftState
	 * @param \DateTime|string|int $rightElement
	 * @param bool $rightState
	 * @return SingleDayTimeInterval
	 */
	public static function create(
		$leftElement,
		bool $leftState,
		$rightElement,
		bool $rightState
	) : SingleDayTimeInterval
	{
		return new SingleDayTimeInterval(
			SingleDayTimeBoundaryFactory::create($leftElement, $leftState),
			SingleDayTimeBoundaryFactory::create($rightElement, $rightState)
		);
	}

}
