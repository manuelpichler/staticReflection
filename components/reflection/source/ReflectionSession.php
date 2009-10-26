<?php

namespace org\pdepend\reflection;

use org\pdepend\reflection\interfaces\ReflectionBuilder;

class ReflectionSession
{
    /**
     *
     * @var array(\org\pdepend\reflection\ReflectionBuilder)
     */
    private $_builders = array();

    public function addBuilder( ReflectionBuilder $builder )
    {
        $this->_builders[] = $builder;
    }

    public function getClass( $class )
    {
        foreach ( $this->_builders as $builder )
        {
            if ( $builder->canBuildClass( $class ) )
            {
                return $builder->buildClass( $class );
            }
        }
        throw new \ReflectionException( 'Cannot resolve class ' . $class );
    }
}