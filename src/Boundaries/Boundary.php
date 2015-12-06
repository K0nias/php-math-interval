<?php

namespace Achse\Math\Interval\Boundaries;

use Achse\Math\Interval\Types\Comparison\ComparisonMethods;
use Achse\Math\Interval\Types\Comparison\IComparable;
use LogicException;
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
		if (!$other instanceof self) { // intentionally self
			throw new LogicException('You cannot compare sheep with the goat.');
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



	/**
	 * @deprecated Boundary should be immutable type.
	 * @param IComparable $value
	 */
	public function setValue(IComparable $value)
	{
		$this->element = $value;
	}



	/**
	 * @deprecated Boundary should be immutable type.
	 * @param bool $state
	 */
	public function setState($state)
	{
		$this->state = $state;
	}



	/**
	 * @param IComparable $element
	 * @param string $type
	 */
	protected function validateElement(IComparable $element, $type)
	{
		if (!$element instanceof $type) {
			throw new LogicException("You have to provide {$type} as element. " . get_class($element) . ' given.');
		}
	}

}
