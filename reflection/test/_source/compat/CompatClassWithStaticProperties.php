<?php
class CompatClassWithStaticProperties
{
    public static $foo = 42,
                  $bar = 23;

    public static $baz = null;

    protected static $x = 23;
    private static $y = 42;
}