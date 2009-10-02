<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */
 
namespace com\example\ioc;

require_once 'Movie.php';
require_once 'SienceFictionMovie.php';

/**
 * The primary dependency injection container.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class Container
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
        $factory->setClassSourceLoader( $this->getClassSourceLoader() );
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
    public function getClassSourceLoader()
    {
        if ( $this->_classSourceLoader === null )
        {
            return BaseClassSourceLoader::get();
        }
        return $this->_classSourceLoader;
    }
}

final class ClassLoader
{
    const TYPE = __CLASS__;

    private static $_namespaceMap = array();

    public static function registerNamespace( $namespace, $directory )
    {
        self::$_namespaceMap[trim( $namespace, '\\' )] = $directory;
    }

    public static function autoload( $className )
    {
        foreach ( self::$_namespaceMap as $namespace => $directory )
        {
            if ( strpos( $className, $namespace ) === 0 ) {
                return self::_load( $className, $namespace, $directory );
            }
        }
        return false;
    }

    private static function _load( $className, $namespace, $directory )
    {
        include self::_createPathname( $className, $namespace, $directory );
    }

    private static function _createPathname( $className, $namespace, $directory )
    {
        $fileName = substr( $className, strlen( $namespace ) + 1 );
        $fileName = strtr( $fileName, '\\', '/' );

        return $directory . '/' . $fileName . '.php' ;
    }
}

ClassLoader::registerNamespace( 'com\example\ioc', dirname( __FILE__ ) );

spl_autoload_register( array( ClassLoader::TYPE, 'autoload' ) );

$container = new Container();
$container->registerSingleton( Movie::TYPE, SienceFictionMovie::TYPE, array( new ConstructorReferenceArgument( SienceFictionMovie::TYPE ) ) );
$container->registerPrototype( SienceFictionMovie::TYPE, SienceFictionMovie::TYPE );

$container->lookup( Movie::TYPE );