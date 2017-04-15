<?php

declare(strict_types=1);

namespace Achse\Math\Interval\SingleDay;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundary;



class SingleDayTimeBoundary extends Boundary
{

	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $element, bool $state)
	{
		$this->validateElement($element, SingleDayTime::class);

		parent::__construct($element, $state);
	}



	/**
	 * @return SingleDayTime
	 */
	public function getValue(): IComparable
	{
		return parent::getValue();
	}

}
