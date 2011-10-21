<?php

namespace reverie;

/**
 * Controller base class.
 * All controllers must inherit this class.
 *
 * Action methods are defined with prefix 'action_'.
 * For example, method Welcome::action_index() will map to
 * page /welcome/index.html.
 */
abstract class Controller
{
    /**
     * Instance of Router.
     */
    protected $route;

    /**
     * Execute the request.
     * Parameters will be bound using bind() before calling
     * the requested method.
     *
     * @see Controller::bind()
     * @param Router $route instance of Router
     * @return object something that can be outputted to the user
     */
    public function execute(Router $route)
    {
        $this->route = $route;
        
        $method = 'action_'.$route->method;

        $refl = new \ReflectionMethod($this, $method);

        $values = array();

        $index = 0;
        
        foreach ($refl->getParameters() as $param)
        {           
            $values[$index] = $this->bind($param, $index);

            $index++;
        }

        return $refl->invokeArgs($this, $values);
    }

    /**
     * Bind given parameter.
     * This method can be overloaded to provide custom binding.
     *
     * @param ReflectionParameter $param parameter to bind
     * @param int $index index of the parameter
     * @return object bound object
     */
    protected function bind(\ReflectionParameter $param, $index)
    {
        $binder = new Binder($param, $this->route, $index);

        return $binder->bind();
    }
}
