<?php

namespace Achse\Tests\Interval\Types;

use Achse\Interval\Types\Comparison\IComparable;
use Nette;
use Tester\Assert;



trait TestComparison
{

	/**
	 * @param IComparable $five
	 * @param IComparable $six
	 */
	public function assertForComparison(IComparable $five, IComparable $six)
	{
		Assert::true($five->lessThen($six));
		Assert::true($five->lessThenOrEqual($six));

		Assert::false($six->lessThen($five));
		Assert::false($six->lessThenOrEqual($five));

		Assert::false($five->equal($six));
		Assert::false($six->equal($five));
	}

}
