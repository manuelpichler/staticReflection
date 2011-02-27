<?php
namespace bug_010498973;

class SelfConstantAsParameterDefaultValue
{
    const SELF = 'self';

    function self($self = self::SELF) {}
}