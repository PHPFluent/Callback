# PHPFluent\Callback
[![Build Status](https://img.shields.io/travis/PHPFluent/Callback/master.svg?style=flat-square)](http://travis-ci.org/PHPFluent/Callback)
[![Code Quality](https://img.shields.io/scrutinizer/g/PHPFluent/Callback/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/PHPFluent/Callback/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/PHPFluent/Callback/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/PHPFluent/Callback/?branch=master)
[![Latest Version](https://img.shields.io/packagist/v/phpfluent/callback.svg?style=flat-square)](https://packagist.org/packages/phpfluent/callback)
[![Total Downloads](https://img.shields.io/packagist/dt/phpfluent/callback.svg?style=flat-square)](https://packagist.org/packages/phpfluent/callback)
[![License](https://img.shields.io/packagist/l/phpfluent/callback.svg?style=flat-square)](https://packagist.org/packages/phpfluent/callback)

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
