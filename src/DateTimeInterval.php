<?php

namespace Achse\Interval;

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
		if ($this->getLeft() > $other->getTill()) {
			return FALSE;
		}

		$modifiedPlus = $this->getRight()->modifyClone("+{$precision}");
		$modifiedMinus = $other->getFrom()->modifyClone("-{$precision}");

		return $modifiedPlus >= $other->getFrom()
		&& $modifiedPlus <= $other->getTill()
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
		$openingYesterday = $other->getFrom()->modifyClone('-1 day');

		return $this->getRight()->format('Y-m-d') === $openingYesterday->format('Y-m-d')
		&& $this->getRight()->format('H:i:s') === '23:59:59'
		&& $other->getFrom()->format('H:i:s') === '00:00:00';
	}



	/**
	 * # Examples:
	 *
	 *     This:  □□□□□□□□□□□■■■■■■■■■■■■■■■■■■■■■■■■□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *
	 *     This:  □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□■■■■■■■■■■■■■■■■■■■■■■■■□□□□□□□□□□
	 *
	 *     This:  □□□□□□□□□□□■■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *
	 *     This:  □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□■■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□
	 *
	 * # False example:
	 *
	 *     This:  □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *
	 * @param DateTimeInterval $other
	 * @return bool
	 */
	public function isColliding(DateTimeInterval $other)
	{
		return $this->isOverlappedFromRightBy($other)
		|| $other->isOverlappedFromRightBy($this)
		|| $this->isContaining($other)
		|| $other->isContaining($this);
	}



	/**
	 * # Examples:
	 *
	 *     This:  □□□□□□□□□□□■■■■■■■■■■■■■■■■■■■■■■■■□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *
	 *     This:  □□□□□□□□□□□■■■■■■■■■■■■■■■■■■■■■■■■□□□□□□□□□□
	 *     Other: □□□□□□□□□□□■■■■■■■■■■■■■■■■■■■■■■■■□□□□□□□□□□
	 *
	 * # False example:
	 *
	 *     This:  □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□■■■■■■■■■■■■■■■■■■■■■■■■□□□□□□□□□□
	 *
	 * @param DateTimeInterval $other
	 * @return bool
	 */
	public function isContaining(DateTimeInterval $other)
	{
		$a = $this;
		$b = $other;

		return
			(
				$b->getFrom() >= $a->getFrom()
				&&
				$b->getTill() <= $a->getTill()
			);
	}



	/**
	 * # Example:
	 *
	 *     This:  □□□□□□□□□□□■■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *
	 * # False examples:
	 *
	 *     This:  □□□□□□□□□□□■■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□■■■■■■■■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *
	 *     This:  □□□□□□□□□□□■■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□□□□□□□■■■■■■■■■■■■□□□□□□□□□
	 *
	 * @param DateTimeInterval $other
	 * @return bool
	 */
	public function isOverlappedFromRightBy(DateTimeInterval $other)
	{
		$a = $this;
		$b = $other;

		return
			(
				$b->getFrom() > $a->getFrom() && $b->getFrom() < $a->getTill()
				&&
				$b->getTill() > $a->getTill()
			);
	}



	/**
	 * @param DateTimeInterval $other
	 * @return static|NULL
	 */
	public function getIntersection(DateTimeInterval $other)
	{
		$a = $this;
		$b = $other;

		if ($a->isContaining($b)) {
			return clone $b;

		} elseif ($b->isContaining($a)) {
			return clone $a;

			// A: □□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□
			// B: □□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□
			//    $b->from   |   | <- $a-till

		} elseif ($a->isOverlappedFromRightBy($b)) {
			return new static($b->getFrom(), $a->getTill());

			// A: □□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□□
			// B: □□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□
			//    $a->from -> |   | <- $b-till

		} elseif ($b->isOverlappedFromRightBy($this)) {
			return new static($a->getFrom(), $b->getTill());
		}

		return NULL;
	}



	/**
	 * @param \DateTime $at
	 * @return bool
	 */
	public function isContainingDateTime(\DateTime $at)
	{
		/** @var DateTime $at */
		$at = DateTime::from($at);

		$leftBoundaryCheck = $this->isLeftOpened() && $this->getLeft()->lessThen($at)
			|| $this->isRightClosed() && $this->getLeft()->lessThenOrEqual($at);

		$rightBoundaryCheck = $this->isRightOpened() && $this->getRight()->greaterThen($at)
			|| $this->isRightClosed() && $this->getRight()->greaterThenOrEqual($at);

		return $leftBoundaryCheck && $rightBoundaryCheck;
	}

}

