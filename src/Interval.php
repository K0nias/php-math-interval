<?php

declare(strict_types=1);

namespace Achse\Math\Interval;

use Achse\Comparable\IComparable;



class Interval
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
	public function __toString(): string
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
	public function getLeft(): Boundary
	{
		return $this->left;
	}



	/**
	 * @return Boundary
	 */
	public function getRight(): Boundary
	{
		return $this->right;
	}



	/**
	 * @param Boundary $left
	 */
	public function setLeft(Boundary $left)
	{
		if ($this->right !== NULL && $left->isGreaterThanOrEqual($this->right)) {
			throw new IntervalRangesInvalidException('Left endpoint cannot be greater then Right endpoint.');
		}

		$this->left = $left;
	}



	/**
	 * @param Boundary $right
	 */
	public function setRight(Boundary $right)
	{
		if ($this->left !== NULL && $this->left->isGreaterThan($right)) {
			throw new IntervalRangesInvalidException('Right endpoint cannot be less then Left endpoint.');
		}

		$this->right = $right;
	}



	/**
	 * @return bool
	 */
	public function isEmpty(): bool
	{
		return $this->isLeftOpened() && $this->left->getValue()->isEqual($this->right->getValue());
	}



	/**
	 * A degenerate interval is any set consisting of a single element.
	 *
	 * @return bool
	 */
	public function isDegenerate(): bool
	{
		return $this->isClosed() && $this->left->isEqual($this->right);
	}



	/**
	 * @return bool
	 */
	public function isProper(): bool
	{
		return !$this->isEmpty() && !$this->isDegenerate();
	}



	/**
	 * An open interval does not include its endpoints.
	 *
	 * @return bool
	 */
	public function isOpened(): bool
	{
		return $this->isLeftOpened() && $this->isRightOpened();
	}



	/**
	 * Does not include left endpoint.
	 *
	 * @return bool
	 */
	public function isLeftOpened(): bool
	{
		return $this->getLeft()->isOpened();
	}



	/**
	 * Does not include right endpoint.
	 *
	 * @return bool
	 */
	public function isRightOpened(): bool
	{
		return $this->getRight()->isOpened();
	}



	/**
	 * @return bool
	 */
	public function isClosed(): bool
	{
		return $this->isLeftClosed() && $this->isRightClosed();
	}



	/**
	 * @return bool
	 */
	public function isLeftClosed(): bool
	{
		return $this->getLeft()->isClosed();
	}



	/**
	 * @return bool
	 */
	public function isRightClosed(): bool
	{
		return $this->getRight()->isClosed();
	}



	/**
	 * @param IComparable $element
	 * @return bool
	 */
	public function isContainingElement(IComparable $element): bool
	{
		$leftBoundaryCheck = (
			$this->isLeftOpened() && $this->getLeft()->getValue()->isLessThan($element)
			||
			$this->isLeftClosed() && $this->getLeft()->getValue()->isLessThanOrEqual($element)
		);

		$rightBoundaryCheck = (
			$this->isRightOpened() && $this->getRight()->getValue()->isGreaterThan($element)
			||
			$this->isRightClosed() && $this->getRight()->getValue()->isGreaterThanOrEqual($element)
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
	public function isContaining(Interval $other): bool
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
	public function isOverlappedFromRightBy(Interval $other): bool
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
	public function isColliding(Interval $other): bool
	{
		return (
			$this->isOverlappedFromRightBy($other)
			|| $other->isOverlappedFromRightBy($this)
		);
	}



	/**
	 * @param Interval $other
	 * @return Interval[]
	 */
	public function getDifference(Interval $other): array
	{
		if (($other = $this->getIntersection($other)) === NULL) {
			return [$this];
		}

		$result = [];

		if (!$other->getLeft()->isEqual($this->getLeft())) { // intentionally not only values but also states
			$result[] = new static(
				$this->getLeft(),
				$this->buildBoundary($other->getLeft()->getValue(), !$other->getLeft()->getState())
			);
		}

		if (!$other->getRight()->isEqual($this->getRight())) { // intentionally not only values but also states
			$result[] = new static(
				$this->buildBoundary($other->getRight()->getValue(), !$other->getRight()->getState()),
				$this->getRight()
			);
		}

		return $result;
	}



	/**
	 * @param Interval $other
	 * @return bool
	 */
	public function isFollowedBy(Interval $other): bool
	{
		return $this->right->getValue()->isEqual($other->left->getValue())
			&& ($this->right->isClosed() || $other->getLeft()->isClosed());
	}



	/**
	 * @param Interval $other
	 * @return Interval[]
	 */
	public function getUnion(Interval $other): array
	{
		if ($this->isFollowedBy($other)) {
			return [new static($this->left, $other->getRight())];

		} elseif ($other->isFollowedBy($this)) {
			return [new static($other->getLeft(), $this->right)];
		}

		return [$this, $other];
	}



	/**
	 * @param IComparable $element
	 * @param bool $state
	 * @return Boundary
	 */
	protected function buildBoundary(IComparable $element, bool $state): Boundary
	{
		return new Boundary($element, $state);
	}



	/**
	 * @param Boundary $left
	 * @param Boundary $right
	 * @param string $type
	 */
	protected function validateBoundaryChecks(Boundary $left, Boundary $right, string $type)
	{
		if (!($left instanceof $type)) {
			throw new InvalidBoundaryTypeException("\$left have to be instance of {$type}.");
		}

		if (!($right instanceof $type)) {
			throw new InvalidBoundaryTypeException("\$right have to be instance of {$type}.");
		}
	}



	/**
	 * @return string
	 */
	protected function getLeftBracket(): string
	{
		return $this->isLeftOpened() ? Boundary::STRING_OPENED_LEFT : Boundary::STRING_CLOSED_LEFT;
	}



	/**
	 * @return string
	 */
	protected function getRightBracket(): string
	{
		return $this->isRightOpened() ? Boundary::STRING_OPENED_RIGHT : Boundary::STRING_CLOSED_RIGHT;
	}



	/**
	 * @param IComparable $element
	 * @return bool
	 */
	private function isElementLeftOpenedBorder(IComparable $element): bool
	{
		return $this->isLeftOpened() && $this->getLeft()->getValue()->isEqual($element);
	}



	/**
	 * @param IComparable $element
	 * @return bool
	 */
	private function isElementRightOpenedBorder(IComparable $element): bool
	{
		return $this->isRightOpened() && $this->getRight()->getValue()->isEqual($element);
	}

}
