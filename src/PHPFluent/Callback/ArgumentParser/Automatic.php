<?php

namespace PHPFluent\Callback\ArgumentParser;

/**
 * Automatically choose the right parser for the job.
 */
class Automatic implements ArgumentParserInterface
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Gets the defined factory.
     *
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(array $arguments, array $parameters)
    {
        $parser = $this->factory->parser($arguments);

        return $parser->parse($arguments, $parameters);
    }
}
