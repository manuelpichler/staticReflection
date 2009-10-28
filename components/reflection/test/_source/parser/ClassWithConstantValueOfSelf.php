<?php
class ClassWithConstantValueOfSelf
{
    const T_BAR = 42,
          T_FOO = self::T_BAR;
}