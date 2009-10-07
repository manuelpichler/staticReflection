<?php

namespace com\example\ioc;

use \com\example\ioc\interfaces\Argument;
use \com\example\ioc\interfaces\Container;
use \com\example\ioc\interfaces\ObjectBuilder;
use \com\example\ioc\interfaces\PropertyArgument;

class ReflectionObjectBuilder implements ObjectBuilder
{
    /**
     * @var ReflectionInjectionFactory
     */
    private $_injectionFactory = null;

    /**
     * @var ReflectionClass
     */
    private $_reflection = null;

    private $_className = null;

    private $_arguments = array();

    private $_properties = array();

    public function __construct( $className )
    {
        $this->_className        = $className;
        $this->_injectionFactory = new ReflectionInjectionFactory();
    }

    public function build( Container $container )
    {
        $arguments = $this->_getArgumentValues( $this->_arguments, $container );

        $this->_validateConstructor( $container, $arguments );
        $instance = $this->_createInstance( $container, $arguments );

        return $this->_inject( $instance, $container );
    }

    private function _createOrReturnReflectionClass()
    {
        if ( $this->_reflection === null )
        {
            $this->_reflection = $this->_createReflectionClass();
        }
        return $this->_reflection;
    }

    private function _createReflectionClass()
    {
        try
        {
            return new \ReflectionClass( $this->_className );
        }
        catch ( \ReflectionException $e )
        {
            throw new \RuntimeException( $e->getMessage() );
        }
    }

    public function addConstructorArgument( Argument $argument )
    {
        $this->_arguments[] = $argument;
    }

    public function addPropertyArgument( PropertyArgument $argument )
    {
        if ( isset( $this->_properties[$argument->getName()] ) === false )
        {
            $this->_properties[$argument->getName()] = array();
        }
        $this->_properties[$argument->getName()][] = $argument;
    }

    private function _validateConstructor( Container $container, array $arguments )
    {
        $validator = ArgumentValidatorFactory::get()->create( $this->_className );
        $validator->validate( '__construct', $arguments );
    }

    private function _createInstance( Container $container, array $arguments )
    {
        if ( $this->_isDefaultConstructorInvocation( $arguments ) )
        {
            return $this->_createOrReturnReflectionClass()->newInstance();
        }
        return $this->_createOrReturnReflectionClass()->newInstanceArgs( $arguments );
    }

    private function _isDefaultConstructorInvocation( array $arguments )
    {
        return ( !$this->_createOrReturnReflectionClass()->hasMethod( '__construct' ) && count( $arguments ) === 0 );
    }

    private function _inject( $object, Container $container )
    {
        foreach ( $this->_properties as $propertyName => $arguments )
        {
            $injection = $this->_injectionFactory->create( $object, $propertyName );
            $injection->inject( $this->_getArgumentValues( $arguments, $container ) );
        }
        return $object;
    }

    private function _getArgumentValues( array $arguments, Container $container )
    {
        $argumentValues = array();
        foreach ( $arguments as $argument )
        {
            $argumentValues[] = $argument->getValue( $container );
        }
        return $argumentValues;
    }
}