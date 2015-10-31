<?php

namespace Achse\Math\Interval\Boundaries;

use Achse\Math\Interval\Types\Comparison\IComparable;
use Achse\Math\Interval\Types\SingleDayTime;
use LogicException;



class SingleDayTimeBoundary extends Boundary
{

	public function __construct(IComparable $element, $state)
	{
		if (!$element instanceof SingleDayTime) {
			throw new LogicException(
				'You have to provide Achse\Interval\Types\SSingleDayTime as element. ' . get_class($element) . ' given.'
			);
		}

		parent::__construct($element, $state);
	}



	/**
	 * @return SingleDayTime
	 */
	public function getValue()
	{
		return parent::getValue();
	}

}
