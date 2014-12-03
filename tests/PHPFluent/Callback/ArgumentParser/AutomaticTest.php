<?php

namespace PHPFluent\Callback\ArgumentParser;

/**
 * @covers PHPFluent\Callback\ArgumentParser\Automatic
 */
class AutomaticTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldAcceptAnInstanceOfFactoryInConstructor()
    {
        $expectedFactory = new Factory();
        $parser = new Automatic($expectedFactory);
        $actualFactory = $parser->getFactory();

        $this->assertSame($expectedFactory, $actualFactory);
    }

    public function testShouldUseFactoryCreateArgumentsParser()
    {
        $arguments = array(1, 3, 5);
        $parameters = array();

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');

        $factoryMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\Factory');
        $factoryMock
            ->expects($this->once())
            ->method('parser')
            ->with($arguments)
            ->will($this->returnValue($argumentParserMock));

        $parser = new Automatic($factoryMock);
        $parser->parse($arguments, $parameters);
    }

    public function testShouldUseFactoredArgumentParserToParseArguments()
    {
        $arguments = array(1, 3, 5);
        $parameters = array();

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');
        $argumentParserMock
            ->expects($this->once())
            ->method('parse')
            ->with($arguments, $parameters);

        $factoryMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\Factory');
        $factoryMock
            ->expects($this->any())
            ->method('parser')
            ->will($this->returnValue($argumentParserMock));

        $parser = new Automatic($factoryMock);
        $parser->parse($arguments, $parameters);
    }

    public function testShouldReturnParsedArguments()
    {
        $expectedParsedArguments = range(50000, 50005);

        $argumentParserMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\ArgumentParserInterface');
        $argumentParserMock
            ->expects($this->any())
            ->method('parse')
            ->will($this->returnValue($expectedParsedArguments));

        $factoryMock = $this->getMock('PHPFluent\\Callback\\ArgumentParser\\Factory');
        $factoryMock
            ->expects($this->any())
            ->method('parser')
            ->will($this->returnValue($argumentParserMock));

        $parser = new Automatic($factoryMock);
        $actualParsedArguments = $parser->parse(array(), array());

        $this->assertSame($expectedParsedArguments, $actualParsedArguments);
    }
}
