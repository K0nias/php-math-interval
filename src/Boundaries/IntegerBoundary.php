<?php

namespace Achse\Math\Interval\Boundaries;

use Achse\Math\Interval\Types\Comparison\IComparable;
use Achse\Math\Interval\Types\Integer;
use LogicException;



class IntegerBoundary extends Boundary
{

	public function __construct(IComparable $element, $state)
	{
		if (!$element instanceof Integer) {
			throw new LogicException(
				'You have to provide Achse\Interval\Types\Integer as element. ' . get_class($element) . ' given.'
			);
		}

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
