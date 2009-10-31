<?php
class CompatMethodWithStaticVariables
{
    public function fooBar()
    {
        static $x = 42;
        static $y = 23, $z;

        static $foo, $bar, $baz = true;
    }
}