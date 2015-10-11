<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Types\Comparison\IComparable;
use Nette\InvalidArgumentException;
use Nette\Object;



class Interval extends Object
{

	const STRING_DELIMITER = ',';

	/**
	 * @var Boundary
	 */
	private $left;

	/**
	 * @var Boundary
	 */
	private $right;



	/**
	 * @param Boundary $left
	 * @param Boundary $right
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		$this->setLeft($left);
		$this->setRight($right);
	}



	/**
	 * @return string
	 */
	public function __toString()
	{
		return (
			$this->getLeftBracket() . $this->getLeft()->getValue()
			. self::STRING_DELIMITER . ' '
			. $this->getRight()->getValue() . $this->getRightBracket()
		);
	}



	/**
	 * @return Boundary
	 */
	public function getLeft()
	{
		return $this->left;
	}



	/**
	 * @return Boundary
	 */
	public function getRight()
	{
		return $this->right;
	}



	/**
	 * @param Boundary $left
	 * @return static
	 */
	public function setLeft(Boundary $left)
	{
		if ($this->right !== NULL && $left->isGreaterThenOrEqual($this->right)) {
			throw new InvalidArgumentException('Left endpoint cannot be greater then Right endpoint.');
		}

		$this->left = $left;
	}



	/**
	 * @param Boundary $right
	 * @return Interval
	 */
	public function setRight(Boundary $right)
	{
		if ($this->left !== NULL && $this->left->isGreaterThen($right)) {
			throw new InvalidArgumentException('Right endpoint cannot be less then Left endpoint.');
		}

		$this->right = $right;
	}



	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return $this->isOpened() && $this->left->isEqual($this->right);
	}



	/**
	 * A degenerate interval is any set consisting of a single element.
	 *
	 * @return bool
	 */
	public function isDegenerate()
	{
		return !$this->isClosed() && $this->left->isEqual($this->right);
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
		return $this->getLeft()->isOpened();
	}



	/**
	 * Does not include right endpoint.
	 *
	 * @return bool
	 */
	public function isRightOpened()
	{
		return $this->getRight()->isOpened();
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
		return $this->getLeft()->isClosed();
	}



	/**
	 * @return bool
	 */
	public function isRightClosed()
	{
		return $this->getRight()->isClosed();
	}



	/**
	 * @param IComparable $element
	 * @return bool
	 */
	public function isContainingElement(IComparable $element)
	{
		$leftBoundaryCheck = (
			$this->isLeftOpened() && $this->getLeft()->getValue()->isLessThen($element)
			||
			$this->isLeftClosed() && $this->getLeft()->getValue()->isLessThenOrEqual($element)
		);

		$rightBoundaryCheck = (
			$this->isRightOpened() && $this->getRight()->getValue()->isGreaterThen($element)
			||
			$this->isRightClosed() && $this->getRight()->getValue()->isGreaterThenOrEqual($element)
		);

		return $leftBoundaryCheck && $rightBoundaryCheck;
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
		return (
			(
				$this->isContainingElement($other->getLeft()->getValue())
				||
				$other->isLeftOpened() && $this->isElementLeftOpenedBorder($other->getLeft()->getValue())
			)
			&&
			(
				$this->isContainingElement($other->getRight()->getValue())
				||
				$other->isRightOpened() && $this->isElementRightOpenedBorder($other->getRight()->getValue())
			)
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
		return (
			$this->isContainingElement($other->getLeft()->getValue())
			&& $other->isContainingElement($this->getRight()->getValue())
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
		return (
			$this->isOverlappedFromRightBy($other)
			|| $other->isOverlappedFromRightBy($this)
		);
	}



	/**
	 * @return string
	 */
	private function getLeftBracket()
	{
		return $this->isLeftOpened() ? Boundary::STRING_OPENED_LEFT : Boundary::STRING_CLOSED_LEFT;
	}



	/**
	 * @return string
	 */
	private function getRightBracket()
	{
		return $this->isRightOpened() ? Boundary::STRING_OPENED_RIGHT : Boundary::STRING_CLOSED_RIGHT;
	}



	/**
	 * @param IComparable $element
	 * @return bool
	 */
	private function isElementLeftOpenedBorder(IComparable $element)
	{
		return $this->isLeftOpened() && $this->getLeft()->getValue()->isEqual($element);
	}



	/**
	 * @param IComparable $element
	 * @return bool
	 */
	private function isElementRightOpenedBorder(IComparable $element)
	{
		return $this->isRightOpened() && $this->getRight()->getValue()->isEqual($element);
	}



	/**
	 * @param Interval $other
	 * @return Interval[]
	 */
	public function getDifference(Interval $other)
	{
		if (($other = $this->getIntersection($other)) === NULL) {
			return [$this];
		}

		$result = [];

		if (!$other->getLeft()->isEqual($this->getLeft())) { // intentionally not only values but also states
			$result[] = new static(
				$this->getLeft(),
				new Boundary($other->getLeft()->getValue(), !$other->getLeft()->getState())
			);
		}

		if (!$other->getRight()->isEqual($this->getRight())) { // intentionally not only values but also states
			$result[] = new static(
				new Boundary($other->getRight()->getValue(), !$other->getRight()->getState()),
				$this->getRight()
			);
		}

		return $result;
	}

}
