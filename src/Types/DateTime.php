<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Types;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\Utils\IntervalUtils;
use LogicException;



class DateTime extends \DateTime implements IComparable
{

	use ComparisonMethods;



	/**
	 * @param string $modify
	 * @return static
	 */
	public function modifyClone(string $modify = ''): DateTime
	{
		$cloned = clone $this;

		return $modify !== '' ? $cloned->modify($modify) : $cloned;
	}



	/**
	 * @inheritdoc
	 * @return static
	 */
	public static function from($time): DateTime
	{
		if ($time instanceof \DateTimeInterface) {
			return new static($time->format('Y-m-d H:i:s.u'), $time->getTimezone());
		} elseif (is_numeric($time)) {
			return (new static('@' . $time))->setTimeZone(new \DateTimeZone(date_default_timezone_get()));
		} else {
			return new static($time);
		}
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



	/**
	 * @return string
	 */
	function __toString(): string
	{
		return $this->format('Y-m-d H:i:s');
	}

}
