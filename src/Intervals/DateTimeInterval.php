<?php

namespace Achse\Interval\Intervals;

use Achse\Interval\Types\Comparison\IComparable;
use Achse\Interval\Types\DateTime;



class DateTimeInterval extends Interval
{

	const PRECISION_ON_SECOND = '1 second';
	const PRECISION_ON_MINUTE = '1 minute';



	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $left, $stateA, IComparable $right, $stateB)
	{
		/** @var DateTime $left */
		$left = DateTime::from($left);
		/** @var DateTime $right */
		$right = DateTime::from($right);

		parent::__construct($left, $stateA, $right, $stateB);
	}



	/**
	 * @param string $from
	 * @param string $till
	 * @return self
	 */
	public static function fromString($from, $till)
	{
		return new static(new DateTime($from), self::CLOSED, new DateTime($till), self::OPENED);
	}



	/**
	 * @return string
	 */
	public function getString()
	{
		return $this->__toString();
	}



	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getLeft()->format('Y-m-d H:i:s') . '-' . $this->getRight()->format('Y-m-d H:i:s');
	}



	/**
	 * # Example:
	 *
	 *     This:  □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *
	 * # False examples:
	 *
	 *     This:  □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□
	 *
	 *     This:  □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *
	 * @param DateTimeInterval $other
	 * @param string $precision
	 * @return bool
	 */
	public function isFollowedBy(DateTimeInterval $other, $precision = self::PRECISION_ON_SECOND)
	{
		if ($this->getLeft() > $other->getRight()) {
			return FALSE;
		}

		$modifiedPlus = $this->getRight()->modifyClone("+{$precision}");
		$modifiedMinus = $other->getLeft()->modifyClone("-{$precision}");

		return $modifiedPlus >= $other->getLeft()
			&& $modifiedPlus <= $other->getRight()
			&& $modifiedMinus <= $this->getRight()
			&& $modifiedMinus >= $this->getLeft();
	}



	/**
	 * # Example:
	 *
	 *     This:  □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *                    23:59:59 >< 00:00:00
	 *
	 * @param DateTimeInterval $other
	 * @return bool
	 */
	public function isFollowedByAtMidnight(DateTimeInterval $other)
	{
		$openingYesterday = $other->getLeft()->modifyClone('-1 day');

		return $this->getRight()->format('Y-m-d') === $openingYesterday->format('Y-m-d')
			&& $this->getRight()->format('H:i:s') === '23:59:59'
			&& $other->getLeft()->format('H:i:s') === '00:00:00';
	}

}

