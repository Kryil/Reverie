<?php

namespace app;

class Tests extends \reverie\Controller
{
    public function action_index()
    {
        return <<<EOL
Hello. This controller has three test cases. They are supposed to be run
from the CLI.
The tests are
  array_binding,
  primitive_binding and
  optional_binding.
  
First one binds one array named 'array'.
  Callable with following commands:
  
  1: REQUEST='array[]=one&array[]=two' php cli.php tests array_binding
  2: php cli.php tests array_binding one

  The results will differ.
  
Second binds two primitives named 'first' and 'second'.
  Callable with following commands:
  
  1: REQUEST='first=one&second=two' php cli.php tests primitive_binding
  2: php cli.php tests primitive_binding one two

  Both should yield same results.
  
Third one binds optional variable 'first'.
  Callable with following commands:
  
  1: php cli.php tests optional_binding
  2: php cli.php tests optional_binding value
  3: REQUEST='first=value' php cli.php tests optional_binding

  First will return "default" while second and third will return "value".

EOL;
    }

    public function action_array_binding(array $array)
    {
        return implode(', ', $array)."\n";
    }

    public function action_primitive_binding($first, $second)
    {
        return "First: $first\nSecond: $second\n";
    }

    public function action_optional_binding($first = "default")
    {
        return $first."\n";
    }
}