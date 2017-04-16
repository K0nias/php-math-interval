<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTime;

use DateTimeInterface;



/**
 * @deprecated Use DateTimeImmutable, always!
 */
final class DateTimeIntervalFactory
{

	/**
	 * @param DateTimeInterface $leftElement
	 * @param bool $leftState
	 * @param DateTimeInterface $rightElement
	 * @param bool $rightState
	 * @return DateTimeInterval
	 */
	public static function create(
		DateTimeInterface $leftElement,
		bool $leftState,
		DateTimeInterface $rightElement,
		bool $rightState
	): DateTimeInterval {
		return new DateTimeInterval(
			DateTimeBoundaryFactory::create($leftElement, $leftState),
			DateTimeBoundaryFactory::create($rightElement, $rightState)
		);
	}

}
