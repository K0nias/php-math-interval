<?php

namespace Achse\Interval\Types\Comparison;

use Nette;



trait ComparisonMethods
{

	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function equal(IComparable $other)
	{
		return $this->compare($other) === 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function lessThen(IComparable $other)
	{
		return $this->compare($other) < 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function lessThenOrEqual(IComparable $other)
	{
		return $this->compare($other) <= 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function greaterThen(IComparable $other)
	{
		return $this->compare($other) > 0;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function greaterThenOrEqual(IComparable $other)
	{
		return $this->compare($other) >= 0;
	}

}
