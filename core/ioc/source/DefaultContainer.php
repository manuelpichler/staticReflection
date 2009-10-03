<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */
 
namespace com\example\ioc;

use com\example\ioc\interfaces\Container;
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

    private $_factories = array();

    public function registerSingleton( $lookupKey, $className, array $args = array() )
    {
        $this->_factories[$lookupKey] = new SingletonObjectFactory( $className, $args );
    }

    public function registerPrototype( $lookupKey, $className, array $args = array() )
    {
        $this->_factories[$lookupKey] = new PrototypeObjectFactory( $className, $args );
    }

    public function lookup( $lookupKey )
    {
        return $this->_tryFindFactoryAndConfigure( $lookupKey )->create( $this );
    }
    
    /**
     *
     * @param string $lookupKey The object lookup key.
     *
     * @return ObjectFactory
     */
    private function _tryFindFactoryAndConfigure( $lookupKey )
    {
        $factory = $this->_tryFindFactory( $lookupKey );
        $factory->setClassSourceLoader( $this->_getClassSourceLoader() );
        return $factory;
    }

    /**
     *
     * @param string $lookupKey The object lookup key.
     *
     * @return ObjectFactory
     */
    private function _tryFindFactory( $lookupKey )
    {
        if ( isset( $this->_factories[$lookupKey] ) )
        {
            return $this->_factories[$lookupKey];
        }
        throw new RuntimeException( "Unknown lookup key given." );        
    }

    /**
     *
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


    public function setClassSourceLoader( ClassSourceLoader $sourceLoader )
    {
        $this->_classSourceLoader = $sourceLoader;
    }
}