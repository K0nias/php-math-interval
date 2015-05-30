<?php

namespace Achse\Interval\Intervals;

use Achse\Interval\Types\Comparison\IComparable;
use Nette\Object;



class NotEmptyInterval extends Object
{

	/**
	 * @var IComparable
	 */
	private $a;

	/**
	 * @var IComparable
	 */
	private $b;



	/**
	 * @param IComparable $a
	 * @param IComparable $b
	 */
	public function __construct(IComparable $a, IComparable $b)
	{
		$this->validateRange($a, $b);

		$this->a = $a;
		$this->b = $b;
	}



	/**
	 * @return IComparable
	 */
	public function getA()
	{
		return $this->a;
	}



	/**
	 * @return IComparable
	 */
	public function getB()
	{
		return $this->b;
	}



	/**
	 * @param IComparable $a
	 * @param IComparable $b
	 */
	protected function validateRange(IComparable $a, IComparable $b)
	{
		if ($a->greaterThen($b)) {
			throw new \LogicException('B cannot be greater then A in any NotEmptyInterval.');
		}
	}

}
