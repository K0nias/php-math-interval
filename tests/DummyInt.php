<?php

declare(strict_types=1);

namespace Achse\Tests\Interval;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\IntervalUtils;



class DummyInt implements IComparable
{

	use ComparisonMethods;

	/**
	 * @var int
	 */
	private $value;



	/**
	 * @param int $value
	 */
	public function __construct(int $value)
	{
		$this->value = $value;
	}



	/**
	 * @return int
	 */
	public function toInt(): int
	{
		return $this->value;
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other): int
	{
		/** @var DummyInt $other */
		IntervalUtils::validateClassType(static::class, $other);

		return $this->value <=> $other->toInt();
	}



	/**
	 * @return string
	 */
	function __toString(): string
	{
		return (string) $this->value;
	}

}
