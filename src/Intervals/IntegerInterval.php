<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\IntegerBoundary;
use Achse\Math\Interval\Types\Integer as IntervalInteger;
use Nette\InvalidArgumentException;



class IntegerInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		if (!($left instanceof IntegerBoundary)) {
			throw new InvalidArgumentException('\$left have to be instance of ' . IntegerBoundary::class);
		}

		if (!($right instanceof IntegerBoundary)) {
			throw new InvalidArgumentException('\$right have to be instance of ' . IntegerBoundary::class);
		}

		parent::__construct($left, $right);
	}



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

