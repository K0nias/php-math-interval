<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundary;



class DateTimeImmutableBoundary extends Boundary
{

	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $element, bool $state)
	{
		$this->validateElement($element, DateTimeImmutable::class);

		parent::__construct($element, $state);
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getValue(): IComparable
	{
		return parent::getValue();
	}

}
