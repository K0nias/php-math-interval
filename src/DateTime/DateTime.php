<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTime;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\Utils;



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
