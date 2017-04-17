[![Downloads this Month](https://img.shields.io/packagist/dm/achse/php-math-interval.svg)](https://packagist.org/packages/achse/php-math-interval)
[![Latest Stable Version](https://poser.pugx.org/achse/php-math-interval/v/stable)](https://github.com/achse/php-math-interval/releases)
[![Build Status](https://travis-ci.org/Achse/php-math-interval.svg?branch=master)](https://travis-ci.org/Achse/php-math-interval)
* Scrutinizer-ci.com: ![](https://scrutinizer-ci.com/g/Achse/php-math-interval/badges/quality-score.png?b=master)
* Codeclimate.com: [![Code Climate](https://codeclimate.com/github/Achse/php-math-interval/badges/gpa.svg)](https://codeclimate.com/github/Achse/php-math-interval)
* Codecov.io: [![codecov.io](https://codecov.io/github/Achse/php-math-interval/coverage.svg?branch=master)](https://codecov.io/github/Achse/php-math-interval?branch=master)
* Coverals.io: [![Coverage Status](https://coveralls.io/repos/github/Achse/php-math-interval/badge.svg?branch=master)](https://coveralls.io/github/Achse/php-math-interval?branch=master)
* SensioLabsInsight: [![SensioLabsInsight](https://insight.sensiolabs.com/projects/dfc506e2-0a86-40c3-8c86-fa01a054a2e1/mini.png)](https://insight.sensiolabs.com/projects/dfc506e2-0a86-40c3-8c86-fa01a054a2e1)
* Versioneye.com: [![Dependency Status](https://www.versioneye.com/user/projects/572070f6fcd19a00518569ef/badge.svg?style=flat)](https://www.versioneye.com/user/projects/572070f6fcd19a00518569ef)


**Note**: *I use this projects for testing many cloud services. That's why you see so many badges here. :)*

## Installation
```
composer require achse/php-math-interval
```

## Usage
### Create an interval
Via factories (most simple):
```php
$interval = DateTimeImmutableIntervalFactory::create(
	new \DateTimeImmutable('2015-10-07 12:00:00'), 
	Boundary::CLOSED, 
	new \DateTimeImmutable('2015-10-07 14:00:00'), 
	Boundary::OPENED
);
echo (string)$interval; // [2015-10-07 12:00:00, 2015-10-07 14:00:00)
```

Directly via constructors:
```php
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable; // We need object implementing IComparable
...

$left = new IntegerBoundary(new DateTimeImmutable('2015-10-07 12:00:00'), Boundary::CLOSED);
$right = new IntegerBoundary(new DateTimeImmutable('2015-10-07 14:00:00'), Boundary::OPENED);
$interval = new DateTimeImmutableInterval($left, $right);
```

Parsed from string (used for tests mostly):
```php
$interval = DateTimeImmutableIntervalStringParser::parse('[2015-01-01 05:00:00, 2015-01-01 10:00:00)');
```

### Methods
Interval object provides powerful tooling for operations with intervals:

```php
use Achse\Math\Interval\Integer\IntegerIntervalStringParser as Parser;
```

* `isContainingElement`
$interval = Parser::parse('[1, 2]');
$interval->isContainingElement(new Integer(2)); // true
$interval->isContainingElement(new Integer(3)); // false
* `getIntersection`
```php
// (1, 3) ∩ (2, 4) ⟺ (2, 3)
Parser::parse('(1, 3)')->getIntersection(Parser::parse('(2, 4)')); // (2, 3)
```
* `getDifference`
```php
// [1, 4] \ [2, 4]
Parser::parse('[1, 4]')->getDifference(Parser::parse('[2, 4]'));
echo ((string) reset($diff)); // [1, 2)
```
* `isContaining`
```php
// [1, 4] contains [2, 3]
Parser::parse('[1, 4]')->isContaining(Parser::parse('[2, 3]')); // true
// [2, 3] NOT contains [1, 4]
Parser::parse('[2, 3]')->isContaining(Parser::parse('[1, 4]')); // false
```
* `isOverlappedFromRightBy`
```php
Parser::parse('[1, 2]')->isOverlappedFromRightBy(Parser::parse('[2, 3]')); // true
Parser::parse('[2, 3]')->isOverlappedFromRightBy(Parser::parse('[1, 2]')); // false
// (1, 2) ~ [2, 3]
Parser::parse('(1, 2)')->isOverlappedFromRightBy(Parser::parse('[2, 3]')); // false
```
* `isColiding`
```php
Parser::parse('[2, 3]')->isColliding(Parser::parse('[1, 2]')); // true
Parser::parse('[1, 2]')->isColliding(Parser::parse('(2, 3)')); // false
```

### Available Types
Library contains intervals for those types:
* `Integer` - classic int,
* `DateTimeImmutable` and `DateTime` (I strongly advise you to use Immutable only),
* `SingeDayTime` - represents "clock-time" from *00:00:00* to *23:59:59*.

**Other types:** `Interval` (its `Boundary`) can contains any type that implements `IComparable`, but if you want
to have type-hinting you may want to write your own `XyInterval` and `XyBoundary` class 
and probably also `Factory` classes.

