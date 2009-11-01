<?php
class MethodWithStaticVariablesFromCommaSeparatedList
{
    public function fooBar()
    {
        static $foo = 42, $bar, $baz = false;
    }
}