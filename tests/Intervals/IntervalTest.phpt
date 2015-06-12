<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

$container = require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Intervals\IntegerInterval;
use Achse\Math\Interval\Intervals\Interval;
use Achse\Math\Interval\Types\Integer as IntegerObj;
use Achse\Math\Interval\Types\Integer;
use Tester\Assert;
use Tester\TestCase;



class IntervalTest extends TestCase
{

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneFourClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneFourOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalTwoThreeClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalTwoThreeOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneTwoClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneThreeOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalTwoFourOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneTwoOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalThreeFourOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalThreeFourClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalTwoFourClosed;

	/**
	 * @var IntegerObj
	 */
	private $one;

	/**
	 * @var IntegerObj
	 */
	private $two;

	/**
	 * @var IntegerObj
	 */
	private $three;

	/**
	 * @var IntegerObj
	 */
	private $four;



	protected function setUp()
	{
		parent::setUp();

		$this->one = new IntegerObj(1);
		$this->two = new IntegerObj(2);
		$this->three = new IntegerObj(3);
		$this->four = new IntegerObj(4);

		$this->intervalOneFourClosed = new IntegerInterval($this->one, Interval::CLOSED, $this->four, Interval::CLOSED);
		$this->intervalOneFourOpened = new IntegerInterval($this->one, Interval::OPENED, $this->four, Interval::OPENED);
		$this->intervalTwoThreeClosed = new IntegerInterval($this->two, Interval::CLOSED, $this->three, Interval::CLOSED);
		$this->intervalTwoThreeOpened = new IntegerInterval($this->two, Interval::OPENED, $this->three, Interval::OPENED);
		$this->intervalOneTwoClosed = new IntegerInterval($this->one, Interval::CLOSED, $this->two, Interval::CLOSED);
		$this->intervalTwoThreeClosed = new IntegerInterval($this->two, Interval::CLOSED, $this->three, Interval::CLOSED);
		$this->intervalOneTwoOpened = new IntegerInterval($this->one, Interval::OPENED, $this->two, Interval::OPENED);
		$this->intervalTwoThreeOpened = new IntegerInterval($this->two, Interval::OPENED, $this->three, Interval::OPENED);
		$this->intervalOneThreeOpened = new IntegerInterval($this->one, Interval::OPENED, $this->three, Interval::OPENED);
		$this->intervalTwoFourOpened = new IntegerInterval($this->two, Interval::OPENED, $this->four, Interval::OPENED);
		$this->intervalOneTwoOpened = new IntegerInterval($this->one, Interval::OPENED, $this->two, Interval::OPENED);
		$this->intervalThreeFourOpened = new IntegerInterval($this->three, Interval::OPENED, $this->four, Interval::OPENED);
		$this->intervalThreeFourClosed = new IntegerInterval($this->three, Interval::CLOSED, $this->four, Interval::CLOSED);
		$this->intervalTwoFourClosed = new IntegerInterval($this->two, Interval::CLOSED, $this->four, Interval::CLOSED);

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


		// (1, 4) NOT contains [3, 4]
		Assert::false($this->intervalOneFourOpened->isContaining($this->intervalThreeFourClosed));
		// (1, 4) NOT contains [1, 2]
		Assert::false($this->intervalOneFourOpened->isContaining($this->intervalOneTwoClosed));

		$left = new IntegerInterval($this->one, Interval::CLOSED, $this->three, Interval::CLOSED);
		$right = new IntegerInterval($this->three, Interval::CLOSED, $this->four, Interval::CLOSED);

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
		$intervalTwoTwoClosed = new IntegerInterval($this->two, Interval::CLOSED, $this->two, Interval::CLOSED);

		$intersection = $this->intervalOneTwoClosed->getIntersection($this->intervalTwoThreeClosed);
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		$intersection = $this->intervalTwoThreeClosed->getIntersection($this->intervalOneTwoClosed);
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		Assert::null($this->intervalOneTwoClosed->getIntersection($this->intervalTwoThreeOpened));
		Assert::null($this->intervalOneTwoOpened->getIntersection($this->intervalTwoThreeClosed));

		$intervalTwoThreeOpened = new IntegerInterval($this->two, Interval::OPENED, $this->three, Interval::OPENED);

		$intersection = $this->intervalOneThreeOpened->getIntersection($this->intervalTwoFourOpened);
		$this->assertInterval($intervalTwoThreeOpened, $intersection);

		$intersection = $this->intervalTwoFourOpened->getIntersection($this->intervalOneThreeOpened);
		$this->assertInterval($intervalTwoThreeOpened, $intersection);
	}



	public function testGetDifference()
	{
		// [1, 4] \ [2, 4]
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalTwoFourClosed);
		Assert::count(1, $diff);
		Assert::equal('[1, 2)', (string) reset($diff));

		// [1, 4] \ [1, 2]
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalOneTwoClosed);
		Assert::count(1, $diff);
		Assert::equal('(2, 4]', (string) reset($diff));

		// [1, 4] \ (2, 4)
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalTwoFourOpened);
		Assert::count(2, $diff);
		Assert::equal('[1, 2]', (string) $diff[0]);
		Assert::equal('[4, 4]', (string) $diff[1]);

		// [1, 4] \ (1, 2)
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalOneTwoOpened);
		Assert::count(2, $diff);
		Assert::equal('[1, 1]', (string) $diff[0]);
		Assert::equal('[2, 4]', (string) $diff[1]);

		// [1, 4] \ [2, 3]
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalTwoThreeClosed);
		Assert::count(2, $diff);
		Assert::equal('[1, 2)', (string) $diff[0]);
		Assert::equal('(3, 4]', (string) $diff[1]);

		// [1, 4] \ (2, 3)
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalTwoThreeOpened);
		Assert::count(2, $diff);
		Assert::equal('[1, 2]', (string) $diff[0]);
		Assert::equal('[3, 4]', (string) $diff[1]);
	}



	/**
	 * @param IntegerInterval $expected
	 * @param IntegerInterval $actual
	 */
	private function assertInterval(IntegerInterval $expected, IntegerInterval $actual)
	{
		Assert::equal($expected->getLeft()->toInt(), $actual->getLeft()->toInt());
		Assert::equal($expected->isLeftClosed(), $actual->isLeftClosed());

		Assert::equal($expected->getRight()->toInt(), $actual->getRight()->toInt());
		Assert::equal($expected->isRightClosed(), $actual->isRightClosed());
	}
	
}



(new IntervalTest())->run();
