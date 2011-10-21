<?php

namespace app\models;

/**
 * An example ViewModel.
 * All public members are automatically bound using the variable name.
 */
class World extends \reverie\ViewModel
{
    public $name;

    public function __toString()
    {
        return (string)$this->name;
    }
}
