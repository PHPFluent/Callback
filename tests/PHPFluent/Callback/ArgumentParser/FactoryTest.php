<?php

namespace PHPFluent\Callback\ArgumentParser;

/**
 * @covers PHPFluent\Callback\ArgumentParser\Factory
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldFactoryCombineParserWhenArgumentsAreEmpty()
    {
        $factory = new Factory();
        $arguments = array();

        $expectedInstanceType = 'PHPFluent\\Callback\\ArgumentParser\\Combine';
        $actualInstance = $factory->parser($arguments);

        $this->assertInstanceOf($expectedInstanceType, $actualInstance);
    }

    public function testShouldFactoryCombineParserWhenEveryArgumentIsNotANumber()
    {
        $factory = new Factory();
        $arguments = array(
            'a' => 1,
            'b' => 2,
            'c' => 3,
        );

        $expectedInstanceType = 'PHPFluent\\Callback\\ArgumentParser\\Combine';
        $actualInstance = $factory->parser($arguments);

        $this->assertInstanceOf($expectedInstanceType, $actualInstance);
    }

    public function testShouldFactoryTypeParserWhenArgumentsKeysAreNumbers()
    {
        $factory = new Factory();
        $arguments = array('a', 'b', 'c');

        $expectedInstanceType = 'PHPFluent\\Callback\\ArgumentParser\\Type';
        $actualInstance = $factory->parser($arguments);

        $this->assertInstanceOf($expectedInstanceType, $actualInstance);
    }

    public function testShouldFactoryTypeParserWhenSomeArgumentKeyIsANumber()
    {
        $factory = new Factory();
        $arguments = array('a' => true, 'b' => 42, 'c');

        $expectedInstanceType = 'PHPFluent\\Callback\\ArgumentParser\\Type';
        $actualInstance = $factory->parser($arguments);

        $this->assertInstanceOf($expectedInstanceType, $actualInstance);
    }
}
