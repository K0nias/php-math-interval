<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\IntervalUtils;
use LogicException;



final class DateTimeImmutable extends \DateTimeImmutable implements IComparable
{

	use ComparisonMethods;



	/**
	 * @param \DateTimeInterface $dateTime
	 * @return static
	 */
	public static function from(\DateTimeInterface $dateTime): DateTimeImmutable
	{
		return new static($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone());
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other): int
	{
		if (!$other instanceof static) {
			throw new LogicException('You cannot compare sheep with the goat.');
		}

		return IntervalUtils::numberCmp($this->getTimestamp(), $other->getTimestamp());
	}

}
