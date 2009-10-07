<?php
namespace com\example\ioc;

use com\example\ioc\interfaces\SourceLoader;

class ObjectFactoryFactory
{
    /**
     * @var ObjectFactoryFactory
     */
    private static $_factory = null;

    /**
     * @return ObjectFactoryFactory
     */
    public static function get()
    {
        if ( self::$_factory === null )
        {
            throw new \RuntimeException( 'No ObjectFactoryFactory configured.' );
        }
        return self::$_factory;
    }

    /**
     * @param ObjectFactoryFactory $factory
     *
     * @return void
     */
    public static function set( ObjectFactoryFactory $factory = null )
    {
        self::$_factory = $factory;
    }
    
    /**
     * @var SourceLoader
     */
    private $_sourceLoader = null;

    /**
     * @param SourceLoader $sourceLoader
     */
    public function __construct( SourceLoader $sourceLoader = null )
    {
        $this->_sourceLoader = $sourceLoader;
    }

    /**
     * @param string          $className
     * @param array(Argument) $args
     *
     * @return PrototypeObjectFactory
     */
    public function createPrototype( $className, array $args )
    {
        return new PrototypeObjectFactory( $className, $args, $this->_sourceLoader );
    }

    /**
     * @param string          $className
     * @param array(Argument) $args
     *
     * @return SingletonObjectFactory
     */
    public function createSingleton( $className, array $args )
    {
        return new SingletonObjectFactory( $className, $args, $this->_sourceLoader );
    }
}