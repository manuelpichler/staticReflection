<?php
namespace org\pdepend\reflection;

use org\pdepend\reflection\interfaces\ReflectionBuilder;

class InternalReflectionBuilder implements ReflectionBuilder
{
    public function canBuildClass( $class )
    {
        return class_exists( $class, false );
    }

    public function buildClass( $class )
    {
        return new \ReflectionClass( $class );
    }
}