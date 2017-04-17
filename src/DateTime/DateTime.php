<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTime;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\IntervalUtils;
use LogicException;



/**
 * @deprecated Use DateTimeImmutable, always!
 */
final class DateTime extends \DateTime implements IComparable
{

	use ComparisonMethods;



	/**
	 * @param \DateTimeInterface $dateTime
	 * @return static
	 */
	public static function from(\DateTimeInterface $dateTime): DateTime
	{
		return new static($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone());
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other): int
	{
		if (!$other instanceof static) {
			throw new LogicException(
				sprintf(
					'You cannot compare sheep with the goat. Type %s expected, but %s given.',
					get_class($this),
					get_class($other)
				)
			);
		}

		return IntervalUtils::numberCmp($this->getTimestamp(), $other->getTimestamp());
	}



	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->format('Y-m-d H:i:s');
	}

}
