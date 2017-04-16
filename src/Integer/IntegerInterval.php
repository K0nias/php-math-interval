<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Integer;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Interval;



final class IntegerInterval extends Interval
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
	public function getLeft(): Boundary
	{
		return parent::getLeft();
	}



	/**
	 * @return IntegerBoundary
	 */
	public function getRight(): Boundary
	{
		return parent::getRight();
	}



	/**
	 * @param IComparable $element
	 * @param bool $state
	 * @return IntegerBoundary
	 */
	protected function buildBoundary(IComparable $element, bool $state): Boundary
	{
		return new IntegerBoundary($element, $state);
	}

}

