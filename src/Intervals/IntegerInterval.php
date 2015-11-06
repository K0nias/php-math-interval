<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\IntegerBoundary;
use Achse\Math\Interval\Types\Comparison\IComparable;



class IntegerInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		$this->validateBoundaryChecks($left, $right, IntegerBoundary::class);

		parent::__construct($left, $right);
	}



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



	/**
	 * @param IComparable $element
	 * @param bool $state
	 * @return IntegerBoundary
	 */
	protected function buildBoundary(IComparable $element, $state)
	{
		return new IntegerBoundary($element, $state);
	}

}

