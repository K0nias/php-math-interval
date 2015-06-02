<?php

namespace Achse\Interval\Types\Comparison;



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
