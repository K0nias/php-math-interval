<?php

namespace Achse\Interval\Types\Comparison;



interface IComparable
{

	const THIS_LESS_THEN_OTHER = -1;
	const THIS_EQUAL_AS_OTHER = 0;
	const THIS_GREATER_THEN_OTHER = 1;



	/**
	 * $this <  $other => -1 (self::THIS_LESS_THEN_OTHER)
	 * $this == $other =>  0 (self::THIS_EQUAL_AS_OTHER)
	 * $this >  $other =>  1 (self::THIS_GREATER_THEN_OTHER)
	 *
	 * @param IComparable $other
	 * @return int (-1, 0, 1)
	 */
	public function compare(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function equal(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function lessThen(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function lessThenOrEqual(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function greaterThen(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function greaterThenOrEqual(IComparable $other);

}
