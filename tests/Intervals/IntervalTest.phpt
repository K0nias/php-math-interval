<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

use Achse\Interval\Intervals\Interval;
use Achse\Interval\Types\Integer as IntervalInteger;
use Tester\Assert;
use Tester\TestCase;



class IntervalTest extends TestCase
{

	/**
	 * @var Interval
	 */
	private $intervalOneFourClosed;

	/**
	 * @var Interval
	 */
	private $intervalOneFourOpened;

	/**
	 * @var Interval
	 */
	private $intervalTwoThreeClosed;

	/**
	 * @var Interval
	 */
	private $intervalTwoThreeOpened;

	/**
	 * @var Interval
	 */
	private $intervalOneTwoClosed;

	/**
	 * @var Interval
	 */
	private $intervalOneThreeOpened;

	/**
	 * @var Interval
	 */
	private $intervalTwoFourOpened;

	/**
	 * @var Interval
	 */
	private $intervalOneTwoOpened;

	/**
	 * @var IntervalInteger
	 */
	private $one;

	/**
	 * @var IntervalInteger
	 */
	private $two;

	/**
	 * @var IntervalInteger
	 */
	private $three;

	/**
	 * @var IntervalInteger
	 */
	private $four;



	protected function setUp()
	{
		parent::setUp();

		$this->one = new IntervalInteger(1);
		$this->two = new IntervalInteger(2);
		$this->three = new IntervalInteger(3);
		$this->four = new IntervalInteger(4);

		$this->intervalOneFourClosed = new Interval($this->one, Interval::CLOSED, $this->four, Interval::CLOSED);
		$this->intervalOneFourOpened = new Interval($this->one, Interval::OPENED, $this->four, Interval::OPENED);
		$this->intervalTwoThreeClosed = new Interval($this->two, Interval::CLOSED, $this->three, Interval::CLOSED);
		$this->intervalTwoThreeOpened = new Interval($this->two, Interval::OPENED, $this->three, Interval::OPENED);
		$this->intervalOneTwoClosed = new Interval($this->one, Interval::CLOSED, $this->two, Interval::CLOSED);
		$this->intervalTwoThreeClosed = new Interval($this->two, Interval::CLOSED, $this->three, Interval::CLOSED);
		$this->intervalOneTwoOpened = new Interval($this->one, Interval::OPENED, $this->two, Interval::OPENED);
		$this->intervalTwoThreeOpened = new Interval($this->two, Interval::OPENED, $this->three, Interval::OPENED);
		$this->intervalOneThreeOpened = new Interval($this->one, Interval::OPENED, $this->three, Interval::OPENED);
		$this->intervalTwoFourOpened = new Interval($this->two, Interval::OPENED, $this->four, Interval::OPENED);
		$this->intervalOneTwoOpened = new Interval($this->one, Interval::OPENED, $this->two, Interval::OPENED);

	}



	public function testIsContainingElement()
	{
		$interval = new Interval($this->one, Interval::CLOSED, $this->two, Interval::CLOSED);
		Assert::true($interval->isContainingElement($this->one));
		Assert::true($interval->isContainingElement($this->two));

		$interval = new Interval($this->one, Interval::OPENED, $this->two, Interval::OPENED);
		Assert::false($interval->isContainingElement($this->one));
		Assert::false($interval->isContainingElement($this->two));

		$interval = new Interval($this->one, Interval::CLOSED, $this->three, Interval::OPENED);
		Assert::true($interval->isContainingElement($this->one));
		Assert::true($interval->isContainingElement($this->two));
		Assert::false($interval->isContainingElement($this->three));

		$interval = new Interval($this->one, Interval::OPENED, $this->three, Interval::CLOSED);
		Assert::false($interval->isContainingElement($this->one));
		Assert::true($interval->isContainingElement($this->two));
		Assert::true($interval->isContainingElement($this->three));
	}



	public function testIsContaining()
	{

		// [1, 4] contains (1, 4)
		Assert::true($this->intervalOneFourClosed->isContaining($this->intervalOneFourOpened));
		// (1, 4) NOT contains [1, 4]
		Assert::false($this->intervalOneFourOpened->isContaining($this->intervalOneFourClosed));

		// [1, 4] contains [2, 3]
		Assert::true($this->intervalOneFourClosed->isContaining($this->intervalTwoThreeClosed));
		// [2, 3] NOT contains [1, 4]
		Assert::false($this->intervalTwoThreeClosed->isContaining($this->intervalOneFourClosed));

		// [1, 4] contains (2, 3)
		Assert::true($this->intervalOneFourClosed->isContaining($this->intervalTwoThreeOpened));
		// (2, 3) NOT contains [1, 4]
		Assert::false($this->intervalTwoThreeOpened->isContaining($this->intervalOneFourClosed));


		$left = new Interval($this->one, Interval::CLOSED, $this->three, Interval::CLOSED);
		$right = new Interval($this->three, Interval::CLOSED, $this->four, Interval::CLOSED);

		Assert::false($left->isContaining($right));
		Assert::false($right->isContaining($left));
	}



	public function testIsOverlappedFromRightBy()
	{
		Assert::true($this->intervalOneTwoClosed->isOverlappedFromRightBy($this->intervalTwoThreeClosed));
		Assert::false($this->intervalTwoThreeClosed->isOverlappedFromRightBy($this->intervalOneTwoClosed));

		// (1, 2) ~ [2, 3]
		Assert::false($this->intervalOneTwoOpened->isOverlappedFromRightBy($this->intervalTwoThreeClosed));
		// [1, 2] ~ (2, 3)
		Assert::false($this->intervalOneTwoClosed->isOverlappedFromRightBy($this->intervalTwoThreeOpened));

		$this->intervalOneThreeOpened = new Interval($this->one, Interval::OPENED, $this->three, Interval::OPENED);
		$this->intervalTwoFourOpened = new Interval($this->two, Interval::OPENED, $this->four, Interval::OPENED);

		Assert::true($this->intervalOneThreeOpened->isOverlappedFromRightBy($this->intervalTwoFourOpened));
	}



	public function testIsColliding()
	{
		Assert::true($this->intervalOneTwoClosed->isColliding($this->intervalTwoThreeClosed));
		Assert::true($this->intervalTwoThreeClosed->isColliding($this->intervalOneTwoClosed));

		Assert::false($this->intervalOneTwoClosed->isColliding($this->intervalTwoThreeOpened));
		Assert::false($this->intervalOneTwoOpened->isColliding($this->intervalTwoThreeClosed));

		Assert::true($this->intervalOneThreeOpened->isColliding($this->intervalTwoFourOpened));
		Assert::true($this->intervalTwoFourOpened->isColliding($this->intervalOneThreeOpened));
	}



	public function testGetIntersection()
	{
		$intervalTwoTwoClosed = new Interval($this->two, Interval::CLOSED, $this->two, Interval::CLOSED);

		$intersection = $this->intervalOneTwoClosed->getIntersection($this->intervalTwoThreeClosed);
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		$intersection = $this->intervalTwoThreeClosed->getIntersection($this->intervalOneTwoClosed);
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		Assert::null($this->intervalOneTwoClosed->getIntersection($this->intervalTwoThreeOpened));
		Assert::null($this->intervalOneTwoOpened->getIntersection($this->intervalTwoThreeClosed));

		$intervalTwoThreeOpened = new Interval($this->two, Interval::OPENED, $this->three, Interval::OPENED);

		$intersection = $this->intervalOneThreeOpened->getIntersection($this->intervalTwoFourOpened);
		$this->assertInterval($intervalTwoThreeOpened, $intersection);

		$intersection = $this->intervalTwoFourOpened->getIntersection($this->intervalOneThreeOpened);
		$this->assertInterval($intervalTwoThreeOpened, $intersection);
	}



	/**
	 * @param Interval $expected
	 * @param Interval $actual
	 */
	private function assertInterval(Interval $expected, Interval $actual)
	{
		Assert::equal($expected->getLeft()->toInt(), $actual->getLeft()->toInt());
		Assert::equal($expected->isLeftClosed(), $actual->isLeftClosed());

		Assert::equal($expected->getRight()->toInt(), $actual->getRight()->toInt());
		Assert::equal($expected->isLeftOpened(), $actual->isLeftOpened());
	}
}



(new IntervalTest())->run();
