<?php
namespace bug_010498973;

class NullConstantAsPropertyDefaultValue
{
    const NULL = 42;

    protected $foo = self::NULL;
}