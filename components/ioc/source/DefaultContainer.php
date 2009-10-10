<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */
 
namespace de\buzz2ee\ioc;

use de\buzz2ee\ioc\interfaces\Container;
use de\buzz2ee\ioc\interfaces\ObjectFactory;
use de\buzz2ee\ioc\interfaces\SourceLoader;
use \de\buzz2ee\ioc\interfaces\BaseSourceLoader;

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
        $factory = $this->getObjectFactoryFactory();
        $this->_register(
            $lookupKey,
            $factory->createSingleton( $className, $args )
        );
    }

    /**
     * @return void
     */
    public function registerPrototype( $lookupKey, $className, array $args = array() )
    {
        $factory = $this->getObjectFactoryFactory();
        $this->_register(
            $lookupKey,
            $factory->createPrototype( $className, $args )
        );
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
            throw new exceptions\CyclicDependencyException();
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

    protected function getObjectFactoryFactory()
    {
        return ObjectFactoryFactory::get();
    }
}
