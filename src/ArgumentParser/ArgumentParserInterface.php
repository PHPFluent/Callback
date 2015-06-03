<?php

namespace PHPFluent\Callback\ArgumentParser;

use PHPFluent\Callback\CallbackParameter;
use PHPFluent\Callback\Exception\InvalidArgumentException;

/**
 * Parses arguments based on parameters.
 */
interface ArgumentParserInterface
{
    /**
     * Parses the given arguments based on the given parameters.
     *
     * @throws InvalidArgumentException When arguments are not valid.
     *
     * @param mixed[]             $arguments  Arguments to be parsed.
     * @param CallbackParameter[] $parameters Parameters to use as base for the parser.
     *
     * @return mixed
     */
    public function parse(array $arguments, array $parameters);
}
