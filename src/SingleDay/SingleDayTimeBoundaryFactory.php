<?php

declare(strict_types=1);

namespace Achse\Math\Interval\SingleDay;



final class SingleDayTimeBoundaryFactory
{

	/**
	 * @param \DateTime|string|int $element
	 * @param bool $state
	 * @return SingleDayTimeBoundary
	 */
	public static function create($element, bool $state): SingleDayTimeBoundary
	{
		return new SingleDayTimeBoundary(SingleDayTime::from($element), $state);
	}

}
