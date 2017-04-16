<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;

use DateTimeInterface;



final class DateTimeImmutableIntervalFactory
{

	/**
	 * @param DateTimeInterface $leftElement
	 * @param bool $leftState
	 * @param DateTimeInterface $rightElement
	 * @param bool $rightState
	 * @return DateTimeImmutableInterval
	 */
	public static function create(
		DateTimeInterface $leftElement,
		bool $leftState,
		DateTimeInterface $rightElement,
		bool $rightState
	): DateTimeImmutableInterval {
		return new DateTimeImmutableInterval(
			DateTimeImmutableBoundaryFactory::create($leftElement, $leftState),
			DateTimeImmutableBoundaryFactory::create($rightElement, $rightState)
		);
	}

}
