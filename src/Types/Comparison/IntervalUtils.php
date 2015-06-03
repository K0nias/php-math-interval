<?php

namespace Achse\Interval\Types\Comparison;

use Nette\Object;



class IntervalUtils extends Object
{

	const PRECISION_ON_SECOND = '1 second';
	const PRECISION_ON_MINUTE = '1 minute';



	/**
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	public static function intCmp($a, $b)
	{
		return ($a - $b) ? ($a - $b) / abs($a - $b) : 0;
	}



	/**
	 * @param \DateTime $a
	 * @param \DateTime $b
	 * @return bool
	 */
	public static function isSameDate(\DateTime $a, \DateTime $b)
	{
		return $a->format('Y-m-d') === $b->format('Y-m-d');
	}

}
