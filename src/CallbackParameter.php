<?php

namespace PHPFluent\Callback;

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
        return $this->reflection->isArray();
    }

    /**
     * @link   http://php.net/ReflectionParameter.isCallable
     *
     * @return bool
     */
    public function isCallable()
    {
        return $this->reflection->isCallable();
    }

    /**
     * Returns if parameter requires an object or not.
     *
     * @return bool
     */
    public function isObject()
    {
        return (null !== $this->reflection->getClass());
    }

    /**
     * Returns TRUE if $value is compatible with parameter or FALSE if not.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isCompatible($value)
    {
        if ($this->isArray()) {
            return is_array($value);
        }

        if ($this->isCallable()) {
            return is_callable($value);
        }

        if ($this->isObject()) {
            return (is_object($value) && $this->reflection->getClass()->isInstance($value));
        }

        return (!is_array($value) && !is_callable($value) && !is_object($value));
    }
}
