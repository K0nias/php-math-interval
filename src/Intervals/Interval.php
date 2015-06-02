<?php

namespace Achse\Interval\Intervals;

use Achse\Interval\Types\Comparison\IComparable;
use Nette\InvalidArgumentException;
use Nette\Object;



class Interval extends Object
{

	const OPENED = TRUE;
	const CLOSED = FALSE;

	/**
	 * @var IComparable
	 */
	private $left;

	/**
	 * @var IComparable
	 */
	private $right;

	/**
	 * @var bool
	 */
	private $leftState;

	/**
	 * @var bool
	 */
	private $rightState;



	/**
	 * @param IComparable $left
	 * @param bool $stateA
	 * @param IComparable $right
	 * @param bool $stateB
	 */
	public function __construct(IComparable $left, $stateA, IComparable $right, $stateB)
	{
		$this->leftState = $stateA;
		$this->rightState = $stateB;

		$this->setLeft($left);
		$this->setRight($right);

	}



	/**
	 * @return IComparable
	 */
	public function getLeft()
	{
		return $this->left;
	}



	/**
	 * @return IComparable
	 */
	public function getRight()
	{
		return $this->right;
	}



	/**
	 * @param IComparable $left
	 * @return static
	 */
	public function setLeft(IComparable $left)
	{
		if ($this->right !== NULL && $left->greaterThen($this->right)) {
			throw new InvalidArgumentException('Left endpoint cannot be greater then Right endpoint.');
		}

		$this->left = $left;

		return $this;
	}



	/**
	 * @param IComparable $right
	 * @return Interval
	 */
	public function setRight(IComparable $right)
	{
		if ($this->left !== NULL && $this->left->greaterThen($right)) {
			throw new InvalidArgumentException('Right endpoint cannot be less then Left endpoint.');
		}

		$this->right = $right;

		return $this;
	}



	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return $this->isOpened() && $this->left->equal($this->right);
	}



	/**
	 * A degenerate interval is any set consisting of a single element.
	 *
	 * @return bool
	 */
	public function isDegenerate()
	{
		return !$this->isClosed() && $this->left->equal($this->right);
	}



	/**
	 * @return bool
	 */
	public function isProper()
	{
		return !$this->isOpened() && !$this->isDegenerate();
	}



	/**
	 * An open interval does not include its endpoints.
	 *
	 * @return bool
	 */
	public function isOpened()
	{
		return $this->isLeftOpened() && $this->isRightOpened();
	}



	/**
	 * Does not include left endpoint.
	 *
	 * @return bool
	 */
	public function isLeftOpened()
	{
		return $this->leftState === self::OPENED;
	}



	/**
	 * Does not include right endpoint.
	 *
	 * @return bool
	 */
	public function isRightOpened()
	{
		return $this->rightState === self::OPENED;
	}



	/**
	 * @return bool
	 */
	public function isClosed()
	{
		return $this->isLeftClosed() && !$this->isRightClosed();
	}



	/**
	 * @return bool
	 */
	public function isLeftClosed()
	{
		return $this->leftState === self::CLOSED;
	}



	/**
	 * @return bool
	 */
	public function isRightClosed()
	{
		return $this->rightState === self::CLOSED;
	}



	/**
	 * @param IComparable $element
	 * @return bool
	 */
	public function isContainingElement(IComparable $element)
	{
		$leftBoundaryCheck = (
			$this->isLeftOpened() && $this->getLeft()->lessThen($element)
			||
			$this->isLeftClosed() && $this->getLeft()->lessThenOrEqual($element)
		);

		$rightBoundaryCheck = (
			$this->isRightOpened() && $this->getRight()->greaterThen($element)
			||
			$this->isRightClosed() && $this->getRight()->greaterThenOrEqual($element)
		);

		return $leftBoundaryCheck && $rightBoundaryCheck;
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
	 * @param Interval $other
	 * @return bool
	 */
	public function isColliding(Interval $other)
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
	 * @param Interval $other
	 * @return bool
	 */
	public function isContaining(Interval $other)
	{
		$a = $this;
		$b = $other;

		return
			(
				$b->getLeft() >= $a->getLeft()
				&&
				$b->getRight() <= $a->getRight()
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
	 * @param Interval $other
	 * @return bool
	 */
	public function isOverlappedFromRightBy(Interval $other)
	{
		$a = $this;
		$b = $other;

		return
			(
				$b->getLeft() > $a->getLeft() && $b->getLeft() < $a->getRight()
				&&
				$b->getRight() > $a->getRight()
			);
	}



	/**
	 * @param Interval $other
	 * @return static|NULL
	 */
	public function getIntersection(Interval $other)
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
			return new static($b->getLeft(), $a->getRight());

			// A: □□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□□
			// B: □□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□
			//    $a->from -> |   | <- $b-till

		} elseif ($b->isOverlappedFromRightBy($this)) {
			return new static($a->getLeft(), $b->getRight());
		}

		return NULL;
	}

}
