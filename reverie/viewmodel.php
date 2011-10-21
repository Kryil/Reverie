<?php

namespace reverie;

class ViewModel
{
    /**
     * Bind this viewmodel with the data from $_REQUEST.
     * Default binding supports only primitives.
     * Overload this method to get custom binding in your models.
     *
     * @param string $prefix optional prefix of the variables
     */
    public function bind($prefix = '')
    {
        if (!empty($prefix)) $prefix .= '.';

        $class = new \ReflectionObject($this);
        $props = $class->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($props as $prop)
        {
            $property = $prop->getName();
            $request = $prefix . $property;

            if (isset($_REQUEST[$request]))
            {
                $this->$property = $_REQUEST[$request];
            }
        }        
    }
}
