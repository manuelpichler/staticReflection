<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

/**
 * Factory that creates an {@link ObjectBuilder} instance.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ObjectBuilderFactory
{
    /**
     * @var ObjectBuilderFactory
     */
    private static $_factory = null;

    /**
     * @return ObjectBuilderFactory
     */
    public static function get()
    {
        if ( self::$_factory === null )
        {
            throw new \RuntimeException( 'No ObjectBuilderFactory configured.' );
        }
        return self::$_factory;
    }

    /**
     * @param ObjectBuilderFactory $factory
     *
     * @return void
     */
    public static function set( ObjectBuilderFactory $factory = null )
    {
        self::$_factory = $factory;
    }

    /**
     * @param string $className
     *
     * @return ObjectBuilder
     * @todo This method should return a non validating object builder and the
     *       reflection version should provide its own factory.
     */
    public function create( $className )
    {
        return new ReflectionObjectBuilder( $className );
    }
}