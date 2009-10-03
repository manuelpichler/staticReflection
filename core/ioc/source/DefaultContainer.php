<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */
 
namespace com\example\ioc;

use com\example\ioc\interfaces\Container;
use com\example\ioc\interfaces\ObjectFactory;
use com\example\ioc\interfaces\ClassSourceLoader;
use \com\example\ioc\interfaces\BaseClassSourceLoader;

/**
 * The primary dependency injection container.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class DefaultContainer implements Container
{
    /**
     * @var ClassSourceLoader
     */
    private $_classSourceLoader = null;

    /**
     * @var ArrayObject
     */
    private $_factories = array();

    /**
     * @var SplObjectStorage
     */
    private $_factoryStorage = null;

    public function __construct()
    {
        $this->_factories      = new \ArrayObject();
        $this->_factoryStorage = new \SplObjectStorage();
    }

    /**
     * @return void
     */
    public function registerSingleton( $lookupKey, $className, array $args = array() )
    {
        $this->_register( $lookupKey, new SingletonObjectFactory( $className, $args ) );
    }

    /**
     * @return void
     */
    public function registerPrototype( $lookupKey, $className, array $args = array() )
    {
        $this->_register( $lookupKey, new PrototypeObjectFactory( $className, $args ) );
    }

    /**
     * @return void
     */
    private function _register( $lookupKey, ObjectFactory $factory )
    {
        $this->_factories->offsetSet( $lookupKey, $factory );
    }

    /**
     * @return stdClass
     */
    public function lookup( $lookupKey )
    {
        $factory = $this->_tryFindFactoryAndConfigure( $lookupKey );
        return $this->_createObject( $factory );
    }
    
    /**
     * @return ObjectFactory
     */
    private function _tryFindFactoryAndConfigure( $lookupKey )
    {
        $factory = $this->_tryFindFactory( $lookupKey );
        $factory = $this->_checkCyclicDependency( $factory );
        $factory->setClassSourceLoader( $this->_getClassSourceLoader() );
        
        return $factory;
    }

    /**
     * @return ObjectFactory
     */
    private function _tryFindFactory( $lookupKey )
    {
        if ( $this->_factories->offsetExists( $lookupKey ) )
        {
            return $this->_factories->offsetGet( $lookupKey );
        }
        throw new \RuntimeException( "Unknown lookup key given." );
    }

    /**
     * @return ObjectFactory
     */
    private function _checkCyclicDependency( ObjectFactory $factory )
    {
        if ( $this->_factoryStorage->contains( $factory ) )
        {
            throw new \LogicException( 'Cyclic dependency detected.' );
        }
        return $factory;
    }

    /**
     * @return stdClass
     */
    private function _createObject( ObjectFactory $factory )
    {
        $this->_factoryStorage->attach( $factory );
        $object = $factory->create( $this );
        $this->_factoryStorage->detach( $factory );

        return $object;
    }

    /**
     * @return ClassSourceLoader
     */
    private function _getClassSourceLoader()
    {
        if ( $this->_classSourceLoader === null )
        {
            return BaseClassSourceLoader::get();
        }
        return $this->_classSourceLoader;
    }

    /**
     * @return void
     */
    public function setClassSourceLoader( ClassSourceLoader $sourceLoader )
    {
        $this->_classSourceLoader = $sourceLoader;
    }
}
