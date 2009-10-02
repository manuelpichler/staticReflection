<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

/**
 * Abstract base implementation of the object factory interface.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BaseObjectFactory implements ObjectFactory
{
    /**
     * @var string
     */
    private $_className = null;
    /**
     * @var ClassSourceLoader
     */
    private $_classSourceLoader = null;

    /**
     * @var array(Argument)
     */
    private $_constructorArguments = array();

    public function __construct( $className, array $arguments )
    {
        $this->_className = $className;

        foreach ( $arguments as $argument )
        {
            $argument->configure( $this );
        }
    }

    public function registerConstructorArgument(ConstructorArgument $argument)
    {
        $this->_constructorArguments[] = $argument;
    }

    public function setClassSourceLoader( ClassSourceLoader $classSourceLoader )
    {
        $this->_classSourceLoader = $classSourceLoader;
    }

    protected function createObject( Container $container )
    {
        $object = $this->_createObject( $container );

        return $object;
    }

    private function _createObject( Container $container )
    {
        $reflection = $this->_createReflectionClass( $container );

        if ( $reflection->getConstructor() === null )
        {
            return $reflection->newInstance();
        }
        return $reflection->newInstanceArgs(
            $this->_getConstructorArguments( $container )
        );
    }

    private function _createReflectionClass( Container $container )
    {
        if ( $this->_classSourceLoader->load( $this->_className ) )
        {
            return new \ReflectionClass( $this->_className );
        }
        throw new LogicException( 'Cannot locate source file for class: ' . $this->_className );
    }

    private function _getConstructorArguments( Container $container )
    {
        $arguments = array();
        foreach ( $this->_constructorArguments as $constructorArgument )
        {
            $arguments[] = $constructorArgument->getValue($container);
        }
        return $arguments;
    }
}