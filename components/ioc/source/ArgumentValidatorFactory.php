<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

/**
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ArgumentValidatorFactory
{
    /**
     * @var ArgumentValidatorFactory
     */
    private static $_factory = null;

    /**
     * @return ArgumentValidatorFactory
     */
    public static function get()
    {
        if ( self::$_factory === null )
        {
            throw new \RuntimeException( 'No ArgumentValidatorFactory configured.' );
        }
        return self::$_factory;
    }

    /**
     * @param ArgumentValidatorFactory $factory
     *
     * @return void
     */
    public static function set( ArgumentValidatorFactory $factory = null )
    {
        self::$_factory = $factory;
    }

    /**
     * @return ArgumentValidator
     */
    public function create( $className )
    {
        return new ReflectionArgumentValidator( $className );
    }
}