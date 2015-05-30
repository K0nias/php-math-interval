<?php

namespace Achse\Interval\Types\Comparison;

use Nette\Object;



class Utils extends Object
{

	/**
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	public static function gmpCmp($a, $b)
	{
		if (function_exists('gmp_cmp')) {
			return gmp_cmp($a, $b);
		}

		return $a === $b
			? IComparable::THIS_EQUAL_AS_OTHER
			: ($a < $b
				? IComparable::THIS_LESS_THEN_OTHER
				: IComparable::THIS_GREATER_THEN_OTHER
			);
	}

}
