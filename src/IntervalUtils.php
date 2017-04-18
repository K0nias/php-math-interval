<?php

declare(strict_types=1);

namespace Achse\Math\Interval;

use InvalidArgumentException;



/**
 * @internal This class is supposed to be used only in this library. Please do not use code from here
 * in your project. Back compatibility of API is not guaranteed here.
 */
final class IntervalUtils
{

	/**
	 * @internal This method is supposed to be used only in this library. Please do not use code from here
	 * in your project. Back compatibility of API is not guaranteed here.
	 *
	 * @param int|float $first
	 * @param int|float $second
	 * @return int
	 */
	public static function numberCmp($first, $second): int
	{
		return ($first - $second) ? (int) (($first - $second) / abs($first - $second)) : 0;
	}



	/**
	 * @internal This method is supposed to be used only in this library. Please do not use code from here
	 * in your project. Back compatibility of API is not guaranteed here.
	 *
	 * @param \DateTimeInterface $first
	 * @param \DateTimeInterface $second
	 * @return bool
	 */
	public static function isSameDate(\DateTimeInterface $first, \DateTimeInterface $second): bool
	{
		return $first->format('Y-m-d') === $second->format('Y-m-d');
	}



	/**
	 * @internal This method is supposed to be used only in this library. Please do not use code from here
	 * in your project. Back compatibility of API is not guaranteed here.
	 *
	 * @param string $expectedClassName
	 * @param mixed $given
	 */
	public static function validateClassType(string $expectedClassName, $given)
	{
		if (!$given instanceof $expectedClassName) {
			throw new InvalidArgumentException(
				sprintf(
					'Value must be type of %s but %s given.',
					$expectedClassName,
					is_object($given) ? get_class($given) : gettype($given)
				)
			);
		}
	}

}
