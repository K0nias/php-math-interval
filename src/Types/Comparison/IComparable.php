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
	public function isLessThan(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isLessThanOrEqual(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isGreaterThan(IComparable $other);



	/**
	 * @param IComparable $other
	 * @return bool
	 */
	public function isGreaterThanOrEqual(IComparable $other);



	/**
	 * @return string
	 */
	public function __toString();

}
