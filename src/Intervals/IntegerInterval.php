<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\IntegerBoundary;
use Achse\Math\Interval\Types\Integer as IntervalInteger;
use Nette\InvalidArgumentException;



class IntegerInterval extends Interval
{

	/**
	 * @return IntegerBoundary
	 */
	public function getLeft()
	{
		return parent::getLeft();
	}



	/**
	 * @return IntegerBoundary
	 */
	public function getRight()
	{
		return parent::getRight();
	}

}

