<?php

namespace Achse\Math\Interval\Boundaries;

use Achse\Math\Interval\Types\Comparison\IComparable;
use Achse\Math\Interval\Types\SingleDayTime;



class SingleDayTimeBoundary extends Boundary
{

	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $element, $state)
	{
		$this->validateElement($element, SingleDayTime::class);

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
