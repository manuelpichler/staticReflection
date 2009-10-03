<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc\interfaces;

/**
 * Abstract base class for a class source file loader.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BaseClassSourceLoader implements ClassSourceLoader
{
    /**
     * @var ClassSourceLoader
     */
    private static $_loader = null;

    /**
     *
     * @return ClassSourceLoader
     */
    public static function get()
    {
        if ( self::$_loader === null )
        {
            self::$_loader = new \com\example\ioc\AutoloadClassSourceLoader();
        }
        return self::$_loader;
    }

    /**
     *
     * @param ClassSourceLoader $classSourceLoader The source loader  instance.
     *
     * @return void
     */
    public static function set( ClassSourceLoader $classSourceLoader = null )
    {
        self::$_loader = $classSourceLoader;
    }
}