<?php

declare(strict_types=1);

namespace Achse\Math\Interval\SingleDay;

use DateTimeInterface;



final class SingleDayTimeBoundaryFactory
{

	/**
	 * @param DateTimeInterface|string $element
	 * @param bool $state
	 * @return SingleDayTimeBoundary
	 */
	public static function create($element, bool $state): SingleDayTimeBoundary
	{
		return new SingleDayTimeBoundary(SingleDayTime::from($element), $state);
	}

}
