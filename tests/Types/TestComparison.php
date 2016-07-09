<?php

namespace Achse\Tests\Interval\Types;

use Achse\Comparable\IComparable;
use Tester\Assert;



trait TestComparison
{

	/**
	 * @param IComparable $five
	 * @param IComparable $six
	 */
	public function assertForComparison(IComparable $five, IComparable $six)
	{
		Assert::true($five->isLessThan($six));
		Assert::true($five->isLessThanOrEqual($six));

		Assert::false($six->isLessThan($five));
		Assert::false($six->isLessThanOrEqual($five));

		Assert::false($five->isEqual($six));
		Assert::false($six->isEqual($five));
	}

}
