<?php
namespace foo\bar
{
    class ClassWithConstantValueOfClass
    {
        const T_FOO    = \Foo::T_FOO;
        const T_BAR    = Bar::T_BAR;
        const T_BAZ    = namespace\Baz::T_BAZ;
        const T_FOOBAR = T_FOOBAR;
        const T_BARFOO = baz\BARFOO;
        const T_FOOBAZ = namespace\FOOBAZ;
    }
}