<?php

declare(strict_types=1);

namespace Achse\Math\Interval;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use LogicException;



class Boundary implements IComparable
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
	public function __construct(IComparable $element, bool $state)
	{
		$this->element = $element;
		$this->state = $state;
	}



	/**
	 * @return IComparable
	 */
	public function getValue(): IComparable
	{
		return $this->element;
	}



	/**
	 * @return bool
	 */
	public function isClosed(): bool
	{
		return $this->state === self::CLOSED;
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other): int
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
	public function isOpened(): bool
	{
		return $this->state === self::OPENED;
	}



	/**
	 * @return bool
	 */
	public function getState(): bool
	{
		return $this->state;
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
	 * @return string
	 */
	public function __toString(): string
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
	 * @param IComparable $element
	 * @param string $type
	 */
	protected function validateElement(IComparable $element, string $type)
	{
		if (!$element instanceof $type) {
			throw new LogicException("You have to provide {$type} as element. " . get_class($element) . ' given.');
		}
	}

}
