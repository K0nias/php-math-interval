<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\Types\Comparison\IComparable;
use Achse\Math\Interval\Types\Integer as IntervalInteger;
use Achse\Math\Interval\Types\Integer;
use Nette\InvalidArgumentException;



/**
 * Classes like this are here because of absence generic in PHP
 * so this provides a tool to work with concrete IComparable type.
 */
class IntegerInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $left, $stateLeft, IComparable $right, $stateRight)
	{
		if (!($left instanceof Integer)) {
			throw new InvalidArgumentException('\$left have to be instance of Achse\Math\Interval\Integer.');
		}

		if (!($right instanceof Integer)) {
			throw new InvalidArgumentException('\$right have to be instance of Achse\Math\Interval\Integer.');
		}

		parent::__construct($left, $stateLeft, $right, $stateRight);
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

