<?php

namespace PHPFluent\Callback\ArgumentParser;

use PHPFluent\Callback\Exception\InvalidArgumentException;

/**
 * Parses arguments based on the parameters names.
 */
class Combine implements ArgumentParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(array $arguments, array $parameters)
    {
        $parsedArguments = array();
        foreach ($parameters as $name => $parameter) {
            if (array_key_exists($name, $arguments)) {
                $parsedArguments[] = $arguments[$name];
                continue;
            }

            if ($parameter->isOptional()) {
                $parsedArguments[] = $parameter->getDefaultValue();
                continue;
            }

            throw new InvalidArgumentException(sprintf('No argument found for parameter "%s"', $name));
        }

        return $parsedArguments;
    }
}
