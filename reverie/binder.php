<?php

namespace reverie;

/**
 * Model binder.
 * Tries to automatically bind all primitives, arrays and primitives in classes.
 */
class Binder
{
    protected $parameter;
    protected $route;
    protected $index;
    protected $prefix;

    /**
     * Create a new binder.
     *
     * @param ReflectionParameter $param the parameter to bind
     * @param Router $route instance of Router
     * @param int $index index of the variable to bind
     * @param string $prefix optional prefix of request variables
     */
    public function __construct(
        \ReflectionParameter $param,
        Router $route,
        $index,
        $prefix = '')
    {
        $this->parameter = $param;
        $this->route = $route;
        $this->index = $index;

        if (!empty($prefix))
            $prefix .= '.';
        $this->prefix = $prefix;
    }

    /**
     * Do the binding.
     *
     * @return object bound object
     */
    public function bind()
    {
        $value = null;
        $class = $this->parameter->getClass();

        if ($class != null)
        {
            $value = $this->bind_class($class);
        }
        else if ($this->parameter->isArray())
        {
            $value = $this->bind_array($this->parameter->getName());
        }
        else
        {
            $value = $this->bind_primitive($this->parameter->getName());
        }

        if ($value === null)
        {
            if (!$this->parameter->isOptional())
                throw new \Exception("Could not bind required parameter!");
            else
                $value = $this->parameter->getDefaultValue();
        }

        return $value;
    }

    /**
     * Bind a class parameter.
     * If the class inherits ViewModel, do the binding using
     * ViewModel::bind().
     *
     * @param ReflectionClass $class class to bind
     * @return object bound class
     */
    protected function bind_class(\ReflectionClass $class)
    {
        $instance = $class->newInstance();

        if ($instance instanceof ViewModel)
        {
            $instance->bind($this->parameter->name);
        }
        else
        {
            // Do a simple bind of each public property,
            // if matching parameter is found from the request.
            $props = $class->getProperties(\ReflectionProperty::IS_PUBLIC);

            foreach ($props as $prop)
            {
                $property = $prop->getName();
                $request = $this->parameter->name .'.'. $property;

                if (isset($_REQUEST[$request]))
                {
                    $instance->$property = $_REQUEST[$request];
                }
            }
        }

        return $instance;
    }

    /**
     * Bind a primitive variable.
     * Tries to dig the value from query parameters first.
     *
     * @param string $name name of the variable
     * @return value for the parameter
     */
    protected function bind_primitive($name)
    {
        if (isset($this->route->parameters[$this->index]))
            return $this->route->parameters[$this->index];

        if (isset($_REQUEST[$name]))
            return $_REQUEST[$name];

        return null;
    }

    /**
     * Bind an array.
     * For now this method only calls bind_primitive() and
     * casts the result to an array.
     *
     * @param string $name name of the variable
     * @return array
     */
    protected function bind_array($name)
    {
        return (array)$this->bind_primitive($name);
    }
}
