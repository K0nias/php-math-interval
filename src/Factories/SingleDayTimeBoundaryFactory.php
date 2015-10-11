<?php

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Boundaries\SingleDayTimeBoundary;
use Achse\Math\Interval\Types\SingleDayTime;
use Nette\Object;



class SingleDayTimeBoundaryFactory extends Object
{

	/**
	 * @param \DateTime|string|int $element
	 * @param bool $state
	 * @return SingleDayTimeBoundary
	 */
	public static function create($element, $state)
	{
		return new SingleDayTimeBoundary(SingleDayTime::from($element), $state);
	}

}
