<?php
class PropertyWithNestedArrayStaticReflectionValue
{
    private $_bar = array(
        array( T_FOO, T_BAR, T_BAZ ),
        array( T_STRING, T_STATIC ),
        array( T_PROTECTED )
    );
}