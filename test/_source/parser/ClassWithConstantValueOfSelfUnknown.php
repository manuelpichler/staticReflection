<?php
class ClassWithConstantValueOfSelfUnknown
{
    const T_FOO = self::T_BAR;
}