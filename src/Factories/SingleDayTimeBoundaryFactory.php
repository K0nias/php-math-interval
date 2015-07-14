<?php

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Types\DateTime;
use Achse\Math\Interval\Types\SingleDayTime;
use Nette\Object;



class SingleDayTimeBoundaryFactory extends Object
{

	/**
	 * @param \DateTime|string|int $element
	 * @param bool $state
	 * @return Boundary
	 */
	public static function create($element, $state)
	{
		/** @var SingleDayTime $element */
		$element = SingleDayTime::from($element);

		return new Boundary($element, $state);
	}

}
