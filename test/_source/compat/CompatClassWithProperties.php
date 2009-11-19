<?php
class CompatClassWithProperties
{
    protected $foo = 42,
              $bar,
              $baz = 23;

    private $_fooBar = 42;
    private $_foo, $_bar, $_baz;

    public $fooBar;
}