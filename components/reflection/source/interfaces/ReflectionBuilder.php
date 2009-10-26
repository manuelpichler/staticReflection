<?php
namespace org\pdepend\reflection\interfaces;

interface ReflectionBuilder
{
    function canBuildClass( $class );

    function buildClass( $class );
}