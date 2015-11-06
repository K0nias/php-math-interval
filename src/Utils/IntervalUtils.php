<?php

namespace Achse\Math\Interval\Utils;

use Nette\Object;



class IntervalUtils extends Object
{

	const PRECISION_ON_SECOND = '1 second';
	const PRECISION_ON_MINUTE = '1 minute';



	/**
	 * @param int $first
	 * @param int $second
	 * @return int
	 */
	public static function intCmp($first, $second)
	{
		return ($first - $second) ? (int) (($first - $second) / abs($first - $second)) : 0;
	}



	/**
	 * @param \DateTime $first
	 * @param \DateTime $second
	 * @return bool
	 */
	public static function isSameDate(\DateTime $first, \DateTime $second)
	{
		return $first->format('Y-m-d') === $second->format('Y-m-d');
	}

}
