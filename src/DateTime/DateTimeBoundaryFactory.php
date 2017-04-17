<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTime;

use DateTimeInterface;



/**
 * @deprecated Use DateTimeImmutable, always!
 */
final class DateTimeBoundaryFactory
{

	/**
	 * @param DateTimeInterface $element
	 * @param bool $state
	 * @return DateTimeBoundary
	 */
	public static function create(DateTimeInterface $element, bool $state): DateTimeBoundary
	{
		return new DateTimeBoundary(DateTime::from($element), $state);
	}

}
