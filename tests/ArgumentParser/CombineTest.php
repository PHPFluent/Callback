<?php

namespace PHPFluent\Callback\ArgumentParser;

/**
 * @covers PHPFluent\Callback\ArgumentParser\Combine
 */
class CombineTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $parameters = array();

    protected function parameterMock($isOptional, $defaultValue = null)
    {
        $parameterMock = $this
            ->getMockBuilder('PHPFluent\\Callback\\CallbackParameter')
            ->disableOriginalConstructor()
            ->getMock();

        $parameterMock
            ->expects($this->any())
            ->method('isOptional')
            ->will($this->returnValue($isOptional));

        $parameterMock
            ->expects($this->any())
            ->method('getDefaultValue')
            ->will($this->returnValue($defaultValue));

        return $parameterMock;
    }

    protected function setUp()
    {
        $this->parser = new Combine();

        $this->parameters['foo'] = $this->parameterMock(false);
        $this->parameters['bar'] = $this->parameterMock(false);
        $this->parameters['baz'] = $this->parameterMock(true, 42);
    }

    protected function tearDown()
    {
        $this->parser = null;

        $this->parameters = array();
    }

    /**
     * @expectedException PHPFluent\Callback\Exception\InvalidArgumentException
     * @expectedExceptionMessage No argument found for parameter "foo"
     */
    public function testShouldThrowsExceptionWhenThereIsNoEnoughArgumentsForParameter()
    {
        $this->parser->parse(
            array('bar' => 2),
            $this->parameters
        );
    }

    public function testShouldReturnTheParametersInSequence()
    {
        $arguments = array('baz' => 3, 'foo' => 1, 'bar' => 2);

        $actualArguments = $this->parser->parse(
            $arguments,
            $this->parameters
        );
        $expectedArguments = array($arguments['foo'], $arguments['bar'], $arguments['baz']);

        $this->assertSame($expectedArguments, $actualArguments);
    }

    public function testShouldReturnTheRightNumberOfParameters()
    {
        $arguments = array('foo' => 1, 'bar' => 2, 'baz' => 3, 'qux' => 4, 'norf' => 5);

        $actualArguments = $this->parser->parse(
            $arguments,
            $this->parameters
        );
        $expectedArguments = array($arguments['foo'], $arguments['bar'], $arguments['baz']);

        $this->assertSame($expectedArguments, $actualArguments);
    }

    public function testShouldReturnTheRightNumberOfParametersEvenWhenOptinalParametersIsNotDefinedByUsingDefaultValue()
    {
        $arguments = array('foo' => 1, 'bar' => 2);

        $actualArguments = $this->parser->parse(
            $arguments,
            $this->parameters
        );
        $expectedArguments = array(1, 2, 42);

        $this->assertSame($expectedArguments, $actualArguments);
    }
}
