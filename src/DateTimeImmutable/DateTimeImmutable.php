<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\Utils;
use DateTimeInterface;



final class DateTimeImmutable extends \DateTimeImmutable implements IComparable
{

	use ComparisonMethods;



	/**
	 * @param DateTimeInterface $dateTime
	 * @return static
	 */
	public static function from(DateTimeInterface $dateTime): DateTimeImmutable
	{
		return new static($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone());
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other): int
	{
		/** @var static $other */
		Utils::validateClassType(static::class, $other);

		return Utils::numberCmp($this->getTimestamp(), $other->getTimestamp());
	}



	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->format('Y-m-d H:i:s');
	}

}
