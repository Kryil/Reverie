<?php

namespace reverie;

/**
 * Minimalist router.
 * Handles the request and creates an instance of the controller.
 */
class Router
{
    /**
     * Controller name
     * @var string
     */
    public $controller;

    /**
     * Requested method
     * @var string
     */
    public $method;

    /**
     * Request parameters
     * @var array
     */
    public $parameters;

    /**
     * Request type. Can be used to determine correct response headers.
     * Defaults to html and is parsed from the end of the path.
     * For example, request /foo/bar.json will yield request_type 'json'
     * @var string
     */
    public $request_type;

    /**
     * Construct a new router with given request path.
     */
    public function __construct($path)
    {
        $this->route($path);
    }

    /**
     * Map the request.
     * This method fills all class members. If you need custom routing,
     * overload this method.
     *
     * @param string $path request path
     */
    protected function route($path)
    {
        $dot = strrpos($path, '.');

        if ($dot === false)
        {
            $path .= '.html';
            $dot = strrpos($path, '.');
        }

        $this->request_type = substr($path, $dot + 1);
        $path = explode('/', substr($path, 0, $dot));

        $this->controller = ucfirst(array_shift($path));
        $this->method = array_shift($path);
        $this->parameters = $path;

        if (empty($this->method))
            $this->method = 'index';
    }

    /**
     * Create an instance of requested controller
     *
     * @return Controller
     */
    public function instantiate()
    {
        $class = APP.'\\'.$this->controller;
        return new $class;
    }
}
