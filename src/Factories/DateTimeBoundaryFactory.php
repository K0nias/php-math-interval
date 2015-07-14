<?php

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Boundaries\DateTimeBoundary;
use Achse\Math\Interval\Types\DateTime;
use Nette\Object;



class DateTimeBoundaryFactory extends Object
{

	/**
	 * @param \DateTime|string|int $element
	 * @param bool $state
	 * @return DateTimeBoundary
	 */
	public static function create($element, $state)
	{
		/** @var DateTime $element */
		$element = DateTime::from($element);

		return new DateTimeBoundary($element, $state);
	}

}
