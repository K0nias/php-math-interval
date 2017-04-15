<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Boundaries;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Types\DateTime;



class DateTimeBoundary extends Boundary
{

	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $element, bool $state)
	{
		$this->validateElement($element, DateTime::class);

		parent::__construct($element, $state);
	}



	/**
	 * @return DateTime
	 */
	public function getValue(): IComparable
	{
		return parent::getValue();
	}

}
