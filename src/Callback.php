<?php

namespace PHPFluent\Callback;

use PHPFluent\Callback\ArgumentParser\ArgumentParserInterface;
use PHPFluent\Callback\ArgumentParser\Automatic;
use PHPFluent\Callback\ArgumentParser\Factory;
use ReflectionFunction;
use ReflectionMethod;

class Callback
{
    /**
     * @var ReflectionFunction|ReflectionMethod
     */
    protected $reflection;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var callable
     */
    protected $callable;

    /**
     * @var ArgumentParserInterface
     */
    protected $argumentParser;

    /**
     * @param callable                          $callable
     * @param ArgumentParserInterface[optional] $argumentParser
     */
    public function __construct(callable $callable, ArgumentParserInterface $argumentParser = null)
    {
        $this->reflection = $this->createReflection($callable);
        $this->callable = $callable;
        $this->argumentParser = $argumentParser ?: new Automatic(new Factory());
    }

    /**
     * @param callable $callable
     *
     * @return ReflectionFunctionAbstract
     */
    private function createReflection(callable $callable)
    {
        if (is_string($callable) && strpos($callable, '::')) {
            $callable = explode('::', $callable);
        }

        if (is_array($callable)) {
            return new ReflectionMethod($callable[0], $callable[1]);
        }

        if (is_object($callable) && method_exists($callable, '__invoke')) {
            return new ReflectionMethod($callable, '__invoke');
        }

        return new ReflectionFunction($callable);
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @return ReflectionFunction|ReflectionMethod
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * @return ArgumentParserInterface
     */
    public function getArgumentParser()
    {
        return $this->argumentParser;
    }

    /**
     * {@inheritdoc}
     *
     * @return CallbackParameter[] A key => value array which the key is the name of the parameter.
     */
    public function getParameters()
    {
        if (null === $this->parameters) {
            $this->parameters = array();
            foreach ($this->reflection->getParameters() as $reflectionParameter) {
                $this->parameters[$reflectionParameter->getName()] = new CallbackParameter($reflectionParameter);
            }
        }

        return $this->parameters;
    }

    /**
     * Invoke callback using arguments.
     *
     * @return mixed
     */
    public function invoke()
    {
        return $this->invokeArguments(func_get_args());
    }

    /**
     * Invoke callback using the given array as arguments.
     *
     * @param array $arguments
     *
     * @return mixed
     */
    public function invokeArguments(array $arguments)
    {
        $parsedArguments = $this->argumentParser->parse($arguments, $this->getParameters());

        return call_user_func_array($this->callable, $parsedArguments);
    }

    /**
     * Executes the callback.
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->invokeArguments(func_get_args());
    }
}
