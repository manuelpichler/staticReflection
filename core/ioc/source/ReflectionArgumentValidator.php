<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

use com\example\ioc\interfaces\ArgumentValidator;
use com\example\ioc\exceptions\ArgumentTypeException;
use com\example\ioc\exceptions\ArgumentNotFoundException;

/**
 * Default implementation of an argument validator.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ReflectionArgumentValidator implements ArgumentValidator
{
    /**
     * @var ReflectionClass
     */
    private $_reflection = null;

    public function __construct( $className )
    {
        $this->_reflection = new \ReflectionClass( $className );
    }

    /**
     * @param string       $methodName
     * @param array(mixed) $arguments
     *
     * @return void
     * @throws ArgumentNotFoundException When a mandatory argument does not exist.
     * @throws ArgumentTypeException When an invalid argument type was given.
     */
    public function validate( $methodName, array $arguments )
    {
        $methodExists = $this->_reflection->hasMethod( $methodName );

        if ( $methodExists === false )
        {
            if ( strtolower( $methodName ) === '__construct' && count( $arguments ) === 0 )
            {
                return;
            }
            throw new \RuntimeException( 'Missing configured method ' . $methodName );
        }

        $method = $this->_reflection->getMethod( $methodName );
        foreach ( $method->getParameters() as $index => $parameter )
        {
            $this->_validate( $parameter, $arguments, $index );
        }
    }

    private function _validate( \ReflectionParameter $parameter, array $arguments, $index )
    {
        if ( $this->_isMandatoryAndMissing( $parameter, $arguments, $index ) )
        {
            throw new ArgumentNotFoundException( $parameter );
        }
        else if ( $this->_isInvalid( $parameter, $arguments, $index ) )
        {
            throw new ArgumentTypeException(
                $this->_getExpectedType( $parameter ),
                $this->_getActualType( $arguments, $index )
            );
        }
    }

    private function _isMandatoryAndMissing( \ReflectionParameter $parameter, array $arguments, $index )
    {
        return ( !$parameter->isOptional() && !array_key_exists( $index, $arguments ) );
    }

    private function _isInvalid( \ReflectionParameter $parameter, array $arguments, $index )
    {
        return (
            $this->_isInvalidNull( $parameter, $arguments, $index ) ||
            $this->_isInvalidType( $parameter, $arguments, $index ) ||
            $this->_isInvalidArray( $parameter, $arguments, $index )
        );
    }

    private function _isInvalidArray( \ReflectionParameter $parameter, array $arguments, $index )
    {
        if ( !$parameter->isArray() )
        {
            return false;
        }
        if ( !isset( $arguments[$index] ) )
        {
            return !$parameter->allowsNull();
        }
        return !is_array( $arguments[$index] );
    }

    private function _isInvalidType( \ReflectionParameter $parameter, array $arguments, $index )
    {
        $class = $parameter->getClass();
        if ( $class === null )
        {
            return false;
        }
        if ( !isset( $arguments[$index] ) )
        {
            return !$parameter->allowsNull();
        }
        if ( !is_object( $arguments[$index] ) )
        {
            return true;
        }
        return !$class->isInstance( $arguments[$index] );
    }

    private function _isInvalidNull( \ReflectionParameter $parameter, array $arguments, $index )
    {
        return ( !$parameter->allowsNull() && !isset( $arguments[$index] ) );
    }

    private function _getExpectedType( \ReflectionParameter $parameter )
    {
        return ( $parameter->getClass() ? $parameter->getClass()->getName() : 'array' );
    }

    private function _getActualType( array $arguments, $index )
    {
        return ( isset( $arguments[$index] ) ? gettype( $arguments[$index] ) : 'NULL' );
    }
}