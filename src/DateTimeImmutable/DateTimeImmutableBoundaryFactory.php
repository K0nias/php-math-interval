<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;


class DateTimeImmutableBoundaryFactory
{

	/**
	 * @param \DateTime|\DateTimeImmutable|string|int $element
	 * @param bool $state
	 * @return DateTimeImmutableBoundary
	 */
	public static function create($element, bool $state): DateTimeImmutableBoundary
	{
		$element = DateTimeImmutable::from($element);

		return new DateTimeImmutableBoundary($element, $state);
	}

}
