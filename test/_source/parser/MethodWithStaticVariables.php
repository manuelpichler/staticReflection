<?php
class MethodWithStaticVariables
{
    public function fooBar()
    {
        static $x;
        static $y;
    }
}