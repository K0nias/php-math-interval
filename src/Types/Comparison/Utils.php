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
	public static function intCmp($a, $b)
	{
		return ($a - $b) ? ($a - $b) / abs($a - $b) : 0;
	}

}
