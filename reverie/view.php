<?php

namespace reverie;

class View
{
    protected $viewpath;

    protected $viewdata;
    
    public function __construct($view, array $data = null)
    {
        $this->viewpath = $view;
        $this->viewdata = array();
        
        if ($data != null)
            $this->set_array($data);
    }

    public function set_array(array $data)
    {
        foreach ($data as $k => $v)
            $this->$k = $v;
    }

    public function __set($key, $value)
    {
        $this->viewdata[$key] = $value;
    }

    public function __get($key)
    {
        return $this->viewdata[$key];
    }

    /**
     * Render the view.
     *
     * @return string rendered view
     */
    public function render()
    {
        return static::capture($this->viewpath, $this->viewdata);
    }

    /**
     * Automatically render the view when outputted as string
     *
     * @return string rendered view
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Actually render the view.
     * Rendering is done in a clean namespace.
     *
     * @param string $viewpath relative path to the view
     * @param array $viewdata variables for the view
     * @return string rendered view
     */
    protected static function capture()
    {
        extract(func_get_arg(1));

        ob_start();
        include REVERIE_ROOT.APP.'/views/'.func_get_arg(0).'.phtml';
        return ob_get_clean();
    }
}
