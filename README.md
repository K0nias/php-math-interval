[![Downloads this Month](https://img.shields.io/packagist/dm/achse/php-math-interval.svg)](https://packagist.org/packages/achse/php-math-interval)
[![Latest Stable Version](https://poser.pugx.org/achse/php-math-interval/v/stable)](https://github.com/achse/php-math-interval/releases)
[![Build Status](https://travis-ci.org/Achse/php-math-interval.svg?branch=master)](https://travis-ci.org/Achse/php-math-interval)
* Scrutinizer-ci.com: ![](https://scrutinizer-ci.com/g/Achse/php-math-interval/badges/quality-score.png?b=master) ![](https://scrutinizer-ci.com/g/Achse/php-math-interval/badges/coverage.png?b=master)
* Codecov.io: [![codecov.io](https://codecov.io/github/Achse/php-math-interval/coverage.svg?branch=master)](https://codecov.io/github/Achse/php-math-interval?branch=master)
* Coverals.io: [![Coverage Status](https://coveralls.io/repos/github/Achse/php-math-interval/badge.svg?branch=master)](https://coveralls.io/github/Achse/php-math-interval?branch=master)

**Note**: *I use this projects for testing many cloud services. That's why you see so many badges here. :)*

## Installation
```
composer require achse/php-math-interval
```

## Usage
### Creating an interval
Via factories (most simple, most encapsulated):
```php
$interval = DateTimeIntervalFactory::create('2015-10-07 12:00:00', Boundary::CLOSED, '2015-10-07 14:00:00', Boundary::OPENED);
```

Directly via constructors:
```php
use Achse\Math\Interval\Types\DateTime; # We need one that implements IComparable
...

$left = new IntegerBoundary(new DateTime('2015-10-07 12:00:00'), Boundary::CLOSED);
$right = new IntegerBoundary(new DateTime('2015-10-07 14:00:00'), Boundary::OPENED);
$interval = new DateTimeInterval($left, $right);
```

Parsed from string (used for tests mostly):
```php
$interval = DateTimeIntervalStringParser::parse('[2015-01-01 05:00:00, 2015-01-01 10:00:00)');
```

### Methods
Interval have all basic operations like:
* `isContainingElement`,
* `getIntersection`,
* `getDifference`,
* and many others.

### Available Types
Library contains intervals for those types:
* `Integer` - classic int,
* `DateTime` - classic DateTime from `Nette\Utils` but implements `IComparable`,
* `SingeDayTime` - represents "clock-time" from *00:00:00* to *23:59:59*.

**Other types:** `Interval` (its `Boundary`) can contains any type that implements `IComparable`, but if you want
to have type-hinting you have to write your own `XyInterval` and `XyBoundary` class and probably also `Factory` classes.

## Motivation, main purpose
This library was created for working properly with *opening hours* of restaurants. If you miss some type or method od
simply just some feature, don't hesitate to send pull request. I'll be really happy. :)
