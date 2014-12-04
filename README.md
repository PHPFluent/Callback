# PHPFluent\Callback
[![Build Status](https://scrutinizer-ci.com/g/PHPFluent/Callback/badges/build.png?b=master)](http://travis-ci.org/PHPFluent/Callback "Build Status")
[![Code Quality](https://scrutinizer-ci.com/g/PHPFluent/Callback/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PHPFluent/Callback/?branch=master "Code Quality")
[![Code Coverage](https://scrutinizer-ci.com/g/PHPFluent/Callback/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/PHPFluent/Callback/?branch=master "Code Coverage")
[![Total Downloads](https://poser.pugx.org/phpfluent/callback/downloads.png)](https://packagist.org/packages/phpfluent/callback "Total Downloads")
[![License](https://poser.pugx.org/phpfluent/callback/license.png)](https://packagist.org/packages/phpfluent/callback "License")
[![Latest Stable Version](https://poser.pugx.org/phpfluent/callback/v/stable.png)](https://packagist.org/packages/phpfluent/callback "Latest Stable Version")

Allows you execute callbacks in a more dynamic way.

## Installation

The package is available on [Packagist](https://packagist.org/packages/phpfluent/callback). You can install it using
[Composer](http://getcomposer.org).

```bash
composer require phpfluent/callback
```

### Dependencies

- PHP 5.4+

## Usage

All examples within this document assume you have the following statement at the beginning of the file:

```php
use PHPFluent\Callback\Callback;
```

## Define your callable

### Closures

```php
$callback = new Callback(
    function () {
        // My callable content.
    }
);
```

### Object methods

```php
$callback = new Callback(array($object, 'methodName'));
```

### User defined functions

```php
$callback = new Callback('my_function');
```

### PHP native functions

```php
$callback = new Callback('str_replace');
```

## Executing your callable

There are many ways you can execute the callable.

### invoke()

```php
$callback->invoke($arg1, $arg2, $arg3);
```

### invokeArguments()

```php
$callback->invokeArguments($arrayArguments);
```

### __invoke()

```php
$callback($arg1, $arg2, $arg3); // call_user_func() and call_user_func_array() will work like a charm
```

## Arguments

If you're reading this document you may be wondering why this library was written since everything written on it
is already possible just using native PHP features.

This library provides more flexibility when defining the arguments to be used when you call your callback. That's useful
when you working with callbacks in a predefined structure but you don't want all arguments.

### Names

`Callback` will execute your callback based on its parameters name.

```php
$callable = new Callback(
    function ($foo, $bar = true) {
        // My callable body
    }
);
$callable->invokeArguments(
    array(
        'foo' => 'PHPFluent',
    )
);
```

### Types

Doesn't matter the order of the arguments, `Callback` will put it in the right order before execute your callable.

```php
$callable = new Callback(
    function (array $array, TypeTwo $typeTwo, $string, $int, TypeThree $typeThree, $optional = 42) {
        // My callable body
    }
);
$callable(array(), new TypeTwo(), new TypeThree(), 'string', 123);
```
