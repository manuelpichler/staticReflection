<?php
namespace compat;

class CompatParameterMagicConstantFunction
{
    public function fooBar( $foo = __FUNCTION__ ) {}
}