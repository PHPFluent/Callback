<?php

namespace PHPFluent\Callback;

use ReflectionParameter;
use stdClass;

/**
 * @covers PHPFluent\Callback\CallbackParameter
 */
class CallbackParameterTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldDefineReflectionOnConstructor()
    {
        $reflection = $this
            ->getMockBuilder('ReflectionParameter')
            ->disableOriginalConstructor()
            ->getMock();

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertSame($reflection, $callbackParameter->getReflection());
    }

    public function testShouldReturnWhenParameterIsAnObject()
    {
        $callable = function (stdClass $parameterName) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertTrue($callbackParameter->isObject());
    }

    public function testShouldReturnWhenParameterIsNotAnObject()
    {
        $callable = function ($parameterName) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertFalse($callbackParameter->isObject());
    }

    public function testShouldReturnWhenParameterIsAnArray()
    {
        $callable = function (array $parameterName) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertTrue($callbackParameter->isArray());
    }

    public function testShouldReturnWhenParameterIsNotAnArray()
    {
        $callable = function (stdClass $parameterName) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertFalse($callbackParameter->isArray());
    }

    public function testShouldReturnWhenParameterIsACallable()
    {
        $callable = function (callable $parameterName) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertTrue($callbackParameter->isCallable());
    }

    public function testShouldReturnWhenParameterIsNotACallable()
    {
        $callable = function ($parameterName) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertFalse($callbackParameter->isCallable());
    }

    public function testShouldReturnWhenParameterIsOptional()
    {
        $callable = function (array $parameterName = array()) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertTrue($callbackParameter->isOptional());
    }

    public function testShouldReturnWhenParameterIsNotOptional()
    {
        $callable = function (stdClass $parameterName) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertFalse($callbackParameter->isOptional());
    }

    public function testShouldReturnDefaultValue()
    {
        $callable = function ($parameterName = null) {};
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertNull($callbackParameter->getDefaultValue());
    }

    public function providerCompatible()
    {
        return array(
            array(function (array $parameterName) {}, array()),
            array(function (callable $parameterName) {}, 'trim'),
            array(function (stdClass $parameterName) {}, new stdClass()),
            array(function ($parameterName) {}, 42),
            array(function ($parameterName) {}, 3.14159265),
            array(function ($parameterName) {}, true),
            array(function ($parameterName) {}, 'string'),
            array(function ($parameterName) {}, fopen(__FILE__, 'r')),
        );
    }

    /**
     * @dataProvider providerCompatible
     */
    public function testShouldReturnWhenValueIsCompatibleWithParameter($callable, $value)
    {
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertTrue($callbackParameter->isCompatible($value));
    }

    public function providerNonCompatible()
    {
        return array(
            array(function (array $parameterName) {}, 42),
            array(function (callable $parameterName) {}, array()),
            array(function (stdClass $parameterName) {}, $this),
            array(function ($parameterName) {}, array()),
            array(function ($parameterName) {}, new stdClass()),
            array(function ($parameterName) {}, function () {}),
        );
    }

    /**
     * @dataProvider providerNonCompatible
     */
    public function testShouldReturnWhenValueIsNotCompatibleWithParameter($callable, $value)
    {
        $reflection = new ReflectionParameter($callable, 'parameterName');

        $callbackParameter = new CallbackParameter($reflection);

        $this->assertFalse($callbackParameter->isCompatible($value));
    }
}
