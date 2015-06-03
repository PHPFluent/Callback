<?php

namespace PHPFluent\Callback\ArgumentParser;

/**
 * Factory of parsers.
 */
class Factory
{
    /**
     * Returns if the given arguments are key => value when keys are not numbers.
     *
     * @param array $arguments
     *
     * @return bool
     */
    protected function isKeyValue(array $arguments)
    {
        $result = true;
        foreach (array_keys($arguments) as $key) {
            $result = $result && !is_numeric($key);
        }

        return $result;
    }

    /**
     * Creates an new argument parser based on the given arguments.
     *
     * @param array $arguments
     *
     * @return ArgumentParserInterface
     */
    public function parser(array $arguments)
    {
        if ($this->isKeyValue($arguments)) {
            return new Combine();
        }

        return new Type();
    }
}
