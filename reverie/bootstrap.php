<?php

namespace reverie;

/**
 * Default autoloader.
 */
function autoload($class)
{
    $class = str_replace('\\', '/', $class);

    if (strpos($class, '/') === false)
    {
        $class = APP.'/'.$class;
    }

    $path = REVERIE_ROOT.strtolower($class).'.php';
    
    if (file_exists($path))
    {
        require $path;
    }
}

spl_autoload_register(__NAMESPACE__.'\autoload');

/**
 * An implementation of parse_str() that does not replace
 * dots with underscores.
 * The array is returned instead of populated by reference.
 * @param string $string query string to parse
 * @return array of variables
 */
function parse_str($string)
{
    $pairs = explode("&", $string);

    $vars = array();
    
    foreach ($pairs as $pair)
    {
        if (!empty($pair))
        {
            $nv = explode("=", $pair);
            $name = urldecode($nv[0]);
            $value = urldecode($nv[1]);

            if (preg_match_all('/(^\w+)?\[([^\]]*)\]/', $nv[0], $m, PREG_SET_ORDER))
            {
                $var = &$vars[$m[0][1]];

                foreach ($m as $vardata)
                {
                    $varname = $vardata[2];

                    if (empty($varname))
                        $var = &$var[];
                    else
                        $var = &$var[$varname];
                }
                $var = $value;
            }
            else
            {
                $vars[$name] = $value;
            }
        }
    }
    return $vars;
}

// Refill _POST from raw input stream
$_POST = parse_str(file_get_contents("php://input"));

// Refill _GET from raw input
if (isset($_SERVER['QUERY_STRING']))
    $_GET = parse_str($_SERVER['QUERY_STRING']);

// Merge _POST and _GET to _REQUEST
$_REQUEST = array_replace($_POST, $_GET);
