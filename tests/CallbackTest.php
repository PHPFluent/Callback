<?php

namespace PHPFluent\Callback;

/**
 * @covers PHPFluent\Callback\Callback
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    protected $calledArguments;

    protected function setUp()
    {
        $this->calledArguments = array();
    }

    public function providerCallback()
    {
        return array(
            array(function () {}, 'ReflectionMethod'),
            array('trim', 'ReflectionFunction'),
            array('PHPFluent\Callback\CallbackTest::myStaticMethod', 'ReflectionMethod'),
            array(array('PHPFluent\Callback\CallbackTest', 'myStaticMethod'), 'ReflectionMethod'),
            array(array($this, 'myNonStaticMethod'), 'ReflectionMethod'),
            array(new WithInvoke(), 'ReflectionMethod'),
        );
    }

    /**
     * @dataProvider providerCallback
     */
    public function testShouldDefineACallableOnConstructor($expectedCallable)
    {
        $callback = new Callback($expectedCallable);

        $actualCallable = $callback->getCallable();

        $this->assertSame($expectedCallable, $actualCallable);
    }

    /**
     * @dataProvider providerCallback
     */
    public function testShouldCreateAReflectionBasedOnCallable($callable, $expectedReflectionIntance)
    {
        $callback = new Callback($callable);

        $actualReflectionObject = $callback->getReflection();

        $this->assertInstanceOf($expectedReflectionIntance, $actualReflectionObject);
    }

    /**
     * @dataProvider providerCallback
     */
    public function testShouldBeAbleToInvokeAnyCallable($callable, $expectedReflectionIntance)
    {
        $callback = new Callback($callable);
        $callback->invoke('foo', 'bar');
    }

    public function testShouldDefineAnArgumentPaeserOnConstructor()
    {
        $expectedArgumentParser = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(function () {}, $expectedArgumentParser);

        $actualArgumentParser = $callback->getArgumentParser();

        $this->assertSame($expectedArgumentParser, $actualArgumentParser);
    }

    public function testShouldHaveAnArgumentPaeserByDefault()
    {
        $callback = new Callback(function () {});

        $expectedArgumentParserClassName = 'PHPFluent\\Callback\\ArgumentParser\\Automatic';
        $actualArgumentParser = $callback->getArgumentParser();

        $this->assertInstanceOf($expectedArgumentParserClassName, $actualArgumentParser);
    }

    public function testShouldReturnAKeyValueArrayWhenGettingParameters()
    {
        $callback = new Callback(function ($a, $b, $c) {});
        $parameters = $callback->getParameters();

        $expectedKeys = array('a', 'b', 'c');
        $actualKeys = array_keys($parameters);

        $this->assertSame($expectedKeys, $actualKeys);
    }

    public function testShouldReturnInstancesOfCallbackParameterWhenGettingParameters()
    {
        $callback = new Callback(function ($parameter) {});
        $parameters = $callback->getParameters();

        $actualParameter = reset($parameters);
        $expectedParameterClassName = 'PHPFluent\\Callback\\CallbackParameter';

        $this->assertInstanceOf($expectedParameterClassName, $actualParameter);
    }

    public function testShouldNotFetchAllParametersEveryTimeParametersAreRequested()
    {
        $callback = new Callback(function ($a, $b, $c = null) {});

        $expectedParameters = $callback->getParameters();
        $actualParameters = $callback->getParameters();

        $this->assertSame($expectedParameters, $actualParameters);
    }

    public function testShouldUseArgumentsParserOnInvokeArgsMethod()
    {
        $expectedArguments = array(1, 2, 3);

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(function ($a, $b, $c) {}, $argumentParserMock);

        $argumentParserMock
            ->expects($this->once())
            ->method('parse')
            ->with($expectedArguments, $callback->getParameters())
            ->will($this->returnValue($expectedArguments));

        $callback->invokeArguments($expectedArguments);
    }

    public function testShouldExecuteCallableWithTheParsedArgumentsOnInvokeArgsMethodWhenIsAReflectionFuntion()
    {
        $actualArguments = null;
        $expectedArguments = array(1, 2, 3);

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(
            function ($a, $b, $c) use (&$actualArguments) {
                $actualArguments = array($a, $b, $c);
            },
            $argumentParserMock
        );

        $argumentParserMock
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($expectedArguments));

        $callback->invokeArguments($expectedArguments);

        $this->assertSame($expectedArguments, $actualArguments);
    }

    public static function myStaticMethod()
    {
    }

    public function myNonStaticMethod($a, $c, $c = 'baz')
    {
        $this->calledArguments = func_get_args();
    }

    public function testShouldExecuteCallableWithTheParsedArgumentsOnInvokeArgsMethodWhenIsAReflectionMethod()
    {
        $expectedArguments = array(1, 2, 3);

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(
            array($this, 'myNonStaticMethod'),
            $argumentParserMock
        );

        $argumentParserMock
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($expectedArguments));

        $callback->invokeArguments($expectedArguments);

        $actualArguments = $this->calledArguments;

        $this->assertSame($expectedArguments, $actualArguments);
    }

    public function testShouldReturnCallableReturnOnInvokeArgsMethod()
    {
        $expectedReturn = 42;

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(
            function () use ($expectedReturn) {
                return $expectedReturn;
            },
            $argumentParserMock
        );

        $argumentParserMock
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue(array()));

        $actualReturn = $callback->invokeArguments(array());

        $this->assertSame($expectedReturn, $actualReturn);
    }

    public function testShouldUseArgumentsParserOnInvokeMethod()
    {
        $expectedArguments = array(1, 2, 3);

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(function ($a, $b, $c) {}, $argumentParserMock);

        $argumentParserMock
            ->expects($this->once())
            ->method('parse')
            ->with($expectedArguments, $callback->getParameters())
            ->will($this->returnValue($expectedArguments));

        $callback->invoke($expectedArguments[0], $expectedArguments[1], $expectedArguments[2]);
    }

    public function testShouldExecuteCallableWithTheParsedArgumentsOnInvokeMethod()
    {
        $actualArguments = null;
        $expectedArguments = array(1, 2, 3);

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(
            function ($a, $b, $c) use (&$actualArguments) {
                $actualArguments = array($a, $b, $c);
            },
            $argumentParserMock
        );

        $argumentParserMock
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($expectedArguments));

        $callback->invoke($expectedArguments[0], $expectedArguments[1], $expectedArguments[2]);

        $this->assertSame($expectedArguments, $actualArguments);
    }

    public function testShouldReturnCallableReturnOnInvokeMethod()
    {
        $expectedReturn = 42;

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(
            function () use ($expectedReturn) {
                return $expectedReturn;
            },
            $argumentParserMock
        );

        $argumentParserMock
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue(array()));

        $actualReturn = $callback->invoke();

        $this->assertSame($expectedReturn, $actualReturn);
    }

    public function testShouldUseArgumentsParserOnMagicInvokeMethod()
    {
        $expectedArguments = array(1, 2, 3);

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(function ($a, $b, $c) {}, $argumentParserMock);

        $argumentParserMock
            ->expects($this->once())
            ->method('parse')
            ->with($expectedArguments, $callback->getParameters())
            ->will($this->returnValue($expectedArguments));

        $callback($expectedArguments[0], $expectedArguments[1], $expectedArguments[2]);
    }

    public function testShouldExecuteCallableWithTheParsedArgumentsOnMagicInvokeMethod()
    {
        $actualArguments = null;
        $expectedArguments = array(1, 2, 3);

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(
            function ($a, $b, $c) use (&$actualArguments) {
                $actualArguments = array($a, $b, $c);
            },
            $argumentParserMock
        );

        $argumentParserMock
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($expectedArguments));

        $callback($expectedArguments[0], $expectedArguments[1], $expectedArguments[2]);

        $this->assertSame($expectedArguments, $actualArguments);
    }

    public function testShouldReturnCallableReturnOnMagicInvokeMethod()
    {
        $expectedReturn = 42;

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $callback = new Callback(
            function () use ($expectedReturn) {
                return $expectedReturn;
            },
            $argumentParserMock
        );

        $argumentParserMock
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue(array()));

        $actualReturn = $callback();

        $this->assertSame($expectedReturn, $actualReturn);
    }
}


class WithInvoke
{
    public function __invoke()
    {
        // Doens't matter
    }
}
