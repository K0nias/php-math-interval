<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Boundaries\DateTimeBoundary;
use Achse\Math\Interval\Types\DateTime;



class DateTimeBoundaryFactory
{

	/**
	 * @param \DateTime|string|int $element
	 * @param bool $state
	 * @return DateTimeBoundary
	 */
	public static function create($element, bool $state): DateTimeBoundary
	{
		/** @var DateTime $element */
		$element = DateTime::from($element);

		return new DateTimeBoundary($element, $state);
	}

}
