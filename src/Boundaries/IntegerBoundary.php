<?php

namespace Achse\Math\Interval\Boundaries;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Types\Integer;



class IntegerBoundary extends Boundary
{

	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $element, $state)
	{
		$this->validateElement($element, Integer::class);

		parent::__construct($element, $state);
	}



	/**
	 * @return Integer
	 */
	public function getValue()
	{
		return parent::getValue();
	}

}
