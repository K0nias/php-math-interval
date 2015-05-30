<?php

namespace Achse\Interval;

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

}
