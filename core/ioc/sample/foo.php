<?php

namespace com\example\ioc\sample;

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

ClassLoader::registerNamespace( 'com\example\ioc\sample' , dirname( __FILE__ ) );
ClassLoader::registerNamespace( 'com\example\ioc', dirname( __FILE__ ) . '/../source' );

spl_autoload_register( array( ClassLoader::TYPE, 'autoload' ) );

$container = new \com\example\ioc\DefaultContainer();
$container->registerSingleton( Movie::TYPE, SienceFictionMovie::TYPE, array( new \com\example\ioc\ConstructorReferenceArgument( SienceFictionMovie::TYPE ) ) );
$container->registerPrototype( SienceFictionMovie::TYPE, SienceFictionMovie::TYPE );

$container->lookup( Movie::TYPE );