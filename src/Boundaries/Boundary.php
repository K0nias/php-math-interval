<?php

namespace Achse\Math\Interval\Boundaries;

use Achse\Math\Interval\Types\Comparison\ComparisonMethods;
use Achse\Math\Interval\Types\Comparison\IComparable;
use Nette\Object;



class Boundary extends Object implements IComparable
{

	use ComparisonMethods;

	const OPENED = TRUE;
	const CLOSED = FALSE;

	const STRING_OPENED_LEFT = '(';
	const STRING_OPENED_RIGHT = ')';
	const STRING_CLOSED_LEFT = '[';
	const STRING_CLOSED_RIGHT = ']';

	/**
	 * @var IComparable
	 */
	private $element;

	/**
	 * @var bool
	 */
	private $state;



	/**
	 * @param IComparable $element
	 * @param bool $state
	 */
	public function __construct(IComparable $element, $state)
	{
		$this->element = $element;
		$this->state = $state;
	}



	/**
	 * @return IComparable
	 */
	public function getValue()
	{
		return $this->element;
	}



	/**
	 * @return bool
	 */
	public function isClosed()
	{
		return $this->state === self::CLOSED;
	}



	/**
	 * @return bool
	 */
	public function isOpened()
	{
		return $this->state === self::OPENED;
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other)
	{
		if (!$other instanceof static) {
			throw new \LogicException('You cannot compare sheep with the goat.');
		}

		$comparison = $this->element->compare($other->element);

		if ($comparison === 0) {
			if ($this->state === $other->state) {
				return 0;
			}

			return $this->isOpened() ? -1 : 1;
		}

		return $comparison;
	}



	/**
	 * @return bool
	 */
	public function getState()
	{
		return $this->state;
	}



	/**
	 * @return string
	 */
	public function __toString()
	{
		return (
			($this->isOpened() ? self::STRING_OPENED_LEFT : self::STRING_CLOSED_LEFT)
			. $this->element
			. ($this->isOpened() ? self::STRING_OPENED_RIGHT : self::STRING_CLOSED_RIGHT)
		);
	}

}
