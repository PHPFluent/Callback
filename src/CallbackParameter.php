<?php

namespace PHPFluent\Callback;

use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

/**
 * {@inheritdoc}
 */
class CallbackParameter
{
    /**
     * @var ReflectionParameter
     */
    protected $reflection;

    /**
     * @param ReflectionParameter $reflection
     */
    public function __construct(ReflectionParameter $reflection)
    {
        $this->reflection = $reflection;
    }

    /**
     * Returns the current defined reflection.
     *
     * @return ReflectionParameter
     */
    public function getReflection()
    {
        return $this->reflection;
    }

    /**
     * @link   http://php.net/ReflectionParameter.getDefaultValue
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        if (!$this->reflection->isDefaultValueAvailable()) {
            return;
        }

        return $this->reflection->getDefaultValue();
    }

    /**
     * @link   http://php.net/ReflectionParameter.isOptional
     *
     * @return bool
     */
    public function isOptional()
    {
        return $this->reflection->isOptional();
    }

    /**
     * @link   http://php.net/ReflectionParameter.isArray
     *
     * @return bool
     */
    public function isArray()
    {
        return $this->reflection->getType() && $this->reflection->getType()->getName() === 'array';
    }

    /**
     * @link   http://php.net/ReflectionParameter.isCallable
     *
     * @return bool
     */
    public function isCallable()
    {
        return $this->reflection->getType() && $this->reflection->getType()->getName() === 'callable';
    }

    /**
     * Returns if parameter requires an object or not.
     *
     * @return bool
     * @throws ReflectionException
     */
    public function isObject()
    {
        return (null !== $this->getClass());
    }

    /**
     * Returns TRUE if $value is compatible with parameter or FALSE if not.
     *
     * @param mixed $value
     *
     * @return bool
     * @throws ReflectionException
     */
    public function isCompatible($value)
    {
        if ($this->isArray()) {
            return is_array($value);
        }

        if ($this->isCallable()) {
            return is_callable($value);
        }

        $class = $this->getClass();
        if ($this->isObject() && $class) {
            return (is_object($value) && $class->isInstance($value));
        }

        return (!is_array($value) && !is_callable($value) && !is_object($value));
    }

    /**
     * Returns Reflection class
     *
     * @return ReflectionClass
     * @throws ReflectionException
     */
    public function getClass()
    {
        return $this->reflection->getType() && !$this->reflection->getType()->isBuiltin()
            ? new ReflectionClass($this->reflection->getType()->getName())
            : null;
    }
}
