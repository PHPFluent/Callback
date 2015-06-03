<?php

namespace PHPFluent\Callback\ArgumentParser;

/**
 * @covers PHPFluent\Callback\ArgumentParser\Type
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    protected function setUp()
    {
        $this->parser = new Type();
    }

    public function testShouldParseCompatibleArguments()
    {
        $arguments = array(array(), new \stdClass());

        $parameterMock = $this
            ->getMockBuilder('PHPFluent\\Callback\\CallbackParameter')
            ->disableOriginalConstructor()
            ->getMock();
        $parameterMock
            ->expects($this->at(0))
            ->method('isCompatible')
            ->with($arguments[0])
            ->will($this->returnValue(false));
        $parameterMock
            ->expects($this->at(1))
            ->method('isCompatible')
            ->with($arguments[1])
            ->will($this->returnValue(true));

        $parameters = array('foo' => $parameterMock);

        $actualArguments = $this->parser->parse($arguments, $parameters);
        $expectedArguments = array($arguments[1]);

        $this->assertSame($expectedArguments, $actualArguments);
    }

    public function testShouldNotUseTheSameArgumentTwice()
    {
        $arguments = array(new \stdClass(), new \stdClass());

        $parameterMock1 = $this
            ->getMockBuilder('PHPFluent\\Callback\\CallbackParameter')
            ->disableOriginalConstructor()
            ->getMock();
        $parameterMock1
            ->expects($this->once())
            ->method('isCompatible')
            ->with($this->logicalOr($arguments[0], $arguments[1]))
            ->will($this->returnValue(true));

        $parameterMock2 = $this
            ->getMockBuilder('PHPFluent\\Callback\\CallbackParameter')
            ->disableOriginalConstructor()
            ->getMock();
        $parameterMock2
            ->expects($this->once())
            ->method('isCompatible')
            ->with($this->logicalOr($arguments[0], $arguments[1]))
            ->will($this->returnValue(true));

        $parameters = array('foo' => $parameterMock1, 'bar' => $parameterMock2);

        $actualArguments = $this->parser->parse($arguments, $parameters);
        $expectedArguments = array($arguments[0], $arguments[1]);

        $this->assertSame($expectedArguments, $actualArguments);
    }

    public function testShouldParseWithDefaultValueWhenValueIsNotDefinedAndParameterIsOptional()
    {
        $defaultValue = 42;
        $arguments = array(new \stdClass(), array());

        $parameterMock = $this
            ->getMockBuilder('PHPFluent\\Callback\\CallbackParameter')
            ->disableOriginalConstructor()
            ->getMock();
        $parameterMock
            ->expects($this->any())
            ->method('isCompatible')
            ->will($this->returnValue(false));
        $parameterMock
            ->expects($this->once())
            ->method('isOptional')
            ->will($this->returnValue(true));
        $parameterMock
            ->expects($this->once())
            ->method('getDefaultValue')
            ->will($this->returnValue($defaultValue));

        $parameters = array('foo' => $parameterMock);

        $actualArguments = $this->parser->parse($arguments, $parameters);
        $expectedArguments = array($defaultValue);

        $this->assertSame($expectedArguments, $actualArguments);
    }

    /**
     * @expectedException PHPFluent\Callback\Exception\InvalidArgumentException
     * @expectedExceptionMessage No compatible found argument with parameter "foo"
     */
    public function testShouldThrowsAnExceptionWhenAnyArgumentIsCompatibleAndParameterIsNotOptional()
    {
        $parameterMock = $this
            ->getMockBuilder('PHPFluent\\Callback\\CallbackParameter')
            ->disableOriginalConstructor()
            ->getMock();
        $parameterMock
            ->expects($this->any())
            ->method('isCompatible')
            ->will($this->returnValue(false));
        $parameterMock
            ->expects($this->once())
            ->method('isOptional')
            ->will($this->returnValue(false));

        $arguments = array(new \stdClass(), array(), 42);
        $parameters = array('foo' => $parameterMock);

        $this->parser->parse($arguments, $parameters);
    }
}
