<?php

namespace Achse\Math\Interval\Types\Comparison;

use Nette;



trait ComparisonMethods
{

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
	public function isLessThen(IComparable $other)
	{
		return $this->compare($other) < 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isLessThenOrEqual(IComparable $other)
	{
		return $this->compare($other) <= 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isGreaterThen(IComparable $other)
	{
		return $this->compare($other) > 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isGreaterThenOrEqual(IComparable $other)
	{
		return $this->compare($other) >= 0;
	}

}
