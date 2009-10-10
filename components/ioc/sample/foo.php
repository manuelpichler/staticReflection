<?php

namespace de\buzz2ee\ioc\sample;

final class ClassLoader
{
    const TYPE = __CLASS__;

    private static $_namespaceMap = array();

    public static function registerNamespace( $namespace, $directory )
    {
        self::$_namespaceMap[trim( $namespace, '\\' )] = $directory;

        krsort( self::$_namespaceMap );
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

ClassLoader::registerNamespace( 'de\buzz2ee\ioc\sample' , dirname( __FILE__ ) );
ClassLoader::registerNamespace( 'de\buzz2ee\ioc', dirname( __FILE__ ) . '/../source' );

spl_autoload_register( array( ClassLoader::TYPE, 'autoload' ) );

$container = new \de\buzz2ee\ioc\DefaultContainer();
$container->registerSingleton( Movie::TYPE, SienceFictionMovie::TYPE, array( new \de\buzz2ee\ioc\ConstructorReferenceArgument( SienceFictionMovie::TYPE ) ) );
$container->registerPrototype( SienceFictionMovie::TYPE, SienceFictionMovie::TYPE );

$container->lookup( Movie::TYPE );