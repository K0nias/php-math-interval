<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;



class DateTimeImmutableIntervalFactory
{

	/**
	 * @param \DateTime|\DateTimeImmutable|string|int $leftElement
	 * @param bool $leftState
	 * @param \DateTime|\DateTimeImmutable|string|int $rightElement
	 * @param bool $rightState
	 * @return DateTimeImmutableInterval
	 */
	public static function create(
		$leftElement,
		bool $leftState,
		$rightElement,
		bool $rightState
	): DateTimeImmutableInterval {
		return new DateTimeImmutableInterval(
			DateTimeImmutableBoundaryFactory::create($leftElement, $leftState),
			DateTimeImmutableBoundaryFactory::create($rightElement, $rightState)
		);
	}

}
