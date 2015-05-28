<?php

namespace Achse\Interval\Types\Comparison;

use Nette;



/**
 * @method int compare(IComparable $other)
 */
trait Comparison
{

	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function equal(IComparable $other)
	{
		return $this->compare($other) === IComparable::THIS_EQUAL_AS_OTHER;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function lessThen(IComparable $other)
	{
		return $this->compare($other) === IComparable::THIS_LESS_THEN_OTHER;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function lessThenOrEqual(IComparable $other)
	{
		$result = $this->compare($other);

		return $result === IComparable::THIS_LESS_THEN_OTHER || $result === IComparable::THIS_EQUAL_AS_OTHER;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function greaterThen(IComparable $other)
	{
		return $this->compare($other) === IComparable::THIS_GREATER_THEN_OTHER;
	}



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function greaterThenOrEqual(IComparable $other)
	{
		$result = $this->compare($other);

		return $result === IComparable::THIS_GREATER_THEN_OTHER || $result === IComparable::THIS_EQUAL_AS_OTHER;
	}

}
