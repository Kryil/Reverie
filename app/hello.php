<?php

namespace app;

/**
 * A hello world controller.
 */
class Hello extends \reverie\Controller
{
    /**
     * Index method.
     * Returns a a hello to given world.
     * The parameter $world is automatically bound using the binding system.
     * The request must contain a variable named 'world.name' if auto binding
     * is desired.
     *
     * @param World $world
     * @return string
     */
    public function action_index(models\World $world)
    {
        $view = new \reverie\View('index');
        $view->world = $world;
        return $view;
    }    
}
