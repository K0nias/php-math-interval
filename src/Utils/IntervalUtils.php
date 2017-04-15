<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Utils;



class IntervalUtils
{

	const PRECISION_ON_SECOND = '1 second';
	const PRECISION_ON_MINUTE = '1 minute';



	/**
	 * @param int|float $first
	 * @param int|float $second
	 * @return int
	 */
	public static function numberCmp($first, $second): int
	{
		return ($first - $second) ? (int) (($first - $second) / abs($first - $second)) : 0;
	}



	/**
	 * @param \DateTime $first
	 * @param \DateTime $second
	 * @return bool
	 */
	public static function isSameDate(\DateTime $first, \DateTime $second): bool
	{
		return $first->format('Y-m-d') === $second->format('Y-m-d');
	}

}
