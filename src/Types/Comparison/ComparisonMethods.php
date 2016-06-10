<?php

namespace Achse\Math\Interval\Types\Comparison;

trait ComparisonMethods
{

	/**
	 * @param IComparable $other
	 * @return int (-1, 0, 1)
	 */
	abstract public function compare(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isEqual(IComparable $other)
	{
		return $this->compare($other) === 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isLessThan(IComparable $other)
	{
		return $this->compare($other) < 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isLessThanOrEqual(IComparable $other)
	{
		return $this->compare($other) <= 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isGreaterThan(IComparable $other)
	{
		return $this->compare($other) > 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isGreaterThanOrEqual(IComparable $other)
	{
		return $this->compare($other) >= 0;
	}

}
