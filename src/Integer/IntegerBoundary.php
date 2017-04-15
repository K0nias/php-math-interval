<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Integer;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundary;



class IntegerBoundary extends Boundary
{

	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $element, bool $state)
	{
		$this->validateElement($element, Integer::class);

		parent::__construct($element, $state);
	}



	/**
	 * @return Integer
	 */
	public function getValue(): IComparable
	{
		return parent::getValue();
	}

}
