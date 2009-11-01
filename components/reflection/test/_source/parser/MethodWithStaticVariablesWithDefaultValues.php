<?php
class MethodWithStaticVariablesWithDefaultValues
{
    private static function fooBar()
    {
        static $foo = 42;
        static $bar = null;
        static $baz = false;
    }
}