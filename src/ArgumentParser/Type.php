<?php

namespace PHPFluent\Callback\ArgumentParser;

use PHPFluent\Callback\Exception\InvalidArgumentException;

/**
 * Parses arguments based on types.
 */
class Type implements ArgumentParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(array $arguments, array $parameters)
    {
        $usedArguments = array();
        $parsedArguments = array();
        foreach ($parameters as $name => $parameter) {
            foreach ($arguments as $key => $value) {
                if (in_array($key, $usedArguments)) {
                    continue;
                }

                if ($parameter->isCompatible($value)) {
                    $usedArguments[] = $key;
                    $parsedArguments[] = $value;
                    continue 2;
                }
            }

            if ($parameter->isOptional()) {
                $parsedArguments[] = $parameter->getDefaultValue();
                continue;
            }

            throw new InvalidArgumentException(sprintf('No compatible found argument with parameter "%s"', $name));
        }

        return $parsedArguments;
    }
}
