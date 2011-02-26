<?php
class Bug009_RuntimeConstantResolving
{
    const T_FOO = self::T_BAR,
          T_BAR = 'T_BAR';
}