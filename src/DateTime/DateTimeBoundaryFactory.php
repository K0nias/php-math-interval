<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTime;

/**
 * @deprecated Use DateTimeImmutable, always!
 */
final class DateTimeBoundaryFactory
{

	/**
	 * @param \DateTime|string|int $element
	 * @param bool $state
	 * @return DateTimeBoundary
	 */
	public static function create($element, bool $state): DateTimeBoundary
	{
		$element = DateTime::from($element);

		return new DateTimeBoundary($element, $state);
	}

}
