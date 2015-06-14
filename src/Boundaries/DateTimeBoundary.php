<?php

namespace Achse\Math\Interval\Boundaries;

use Achse\Math\Interval\Types\Comparison\IComparable;
use Achse\Math\Interval\Types\DateTime;



class DateTimeBoundary extends Boundary
{

	public function __construct(IComparable $element, $state)
	{
		if (!$element instanceof DateTime) {
			throw new \LogicException(
				'You have to provide Achse\Interval\Types\DateTime as element. ' . get_class($element) . ' given.'
			);
		}

		parent::__construct($element, $state);
	}



	/**
	 * @return DateTime
	 */
	public function getValue()
	{
		return parent::getValue();
	}

}
