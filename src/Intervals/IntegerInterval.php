<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\Types\Integer as IntervalInteger;



/**
 * Classes like this are here because of absence generic in PHP
 * so this provides a tool to work with concrete IComparable type.
 */
class IntegerInterval extends Interval
{

	/**
	 * @return IntervalInteger
	 */
	public function getLeft()
	{
		return parent::getLeft();
	}



	/**
	 * @return IntervalInteger
	 */
	public function getRight()
	{
		return parent::getRight();
	}

}

