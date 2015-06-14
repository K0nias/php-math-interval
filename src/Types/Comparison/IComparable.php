<?php

namespace Achse\Math\Interval\Types\Comparison;



interface IComparable
{


	/**
	 * $this <  $other =>  returns less then 0
	 * $this == $other =>  returns 0
	 * $this >  $other =>  returns greater then 0
	 *
	 * @param IComparable $other
	 * @return int (-1, 0, 1)
	 */
	public function compare(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isEqual(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isLessThen(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isLessThenOrEqual(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isGreaterThen(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isGreaterThenOrEqual(IComparable $other);



	/**
	 * @return string
	 */
	public function __toString();

}
