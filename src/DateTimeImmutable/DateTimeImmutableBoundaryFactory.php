<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;

use DateTimeInterface;



final class DateTimeImmutableBoundaryFactory
{

	/**
	 * @param DateTimeInterface $element
	 * @param bool $state
	 * @return DateTimeImmutableBoundary
	 */
	public static function create(DateTimeInterface $element, bool $state): DateTimeImmutableBoundary
	{
		return new DateTimeImmutableBoundary(DateTimeImmutable::from($element), $state);
	}

}
