<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace de\buzz2ee\aop\pointcut;

use \de\buzz2ee\aop\interfaces\PointcutMatcher;

/**
 * Default matcher factory class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutMatcherFactory
{
    /**
     * @var PointcutMatcherFactory
     */
    private static $_factory = null;

    /**
     * @return PointcutMatcherFactory
     */
    public static function get()
    {
        if ( self::$_factory === null )
        {
            throw new \RuntimeException( 'No PointcutMatcherFactory configured.' );
        }
        return self::$_factory;
    }

    /**
     * @param PointcutMatcherFactory $factory
     *
     * @return void
     */
    public static function set( PointcutMatcherFactory $factory = null )
    {
        self::$_factory = $factory;
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $visibility
     *
     * @return PointcutExecutionMatcher
     */
    public function createExecutionMatcher( $className, $methodName, $visibility )
    {
        return new PointcutExecutionMatcher( $className, $methodName, $visibility );
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return PointcutNamedMatcher
     */
    public function createNamedMatcher( $className, $methodName )
    {
        return new PointcutNamedMatcher( $className, $methodName );
    }

    /**
     * @param PointcutMatcher $matcher
     *
     * @return PointcutNotMatcher
     */
    public function createNotMatcher( PointcutMatcher $matcher )
    {
        return new PointcutNotMatcher( $matcher );
    }

    /**
     * @param PointcutMatcher $left
     * @param PointcutMatcher $right
     *
     * @return PointcutAndMatcher
     */
    public function createAndMatcher( PointcutMatcher $left, PointcutMatcher $right )
    {
        return new PointcutAndMatcher( $left, $right );
    }

    /**
     * @param PointcutMatcher $left
     * @param PointcutMatcher $right
     *
     * @return PointcutOrMatcher
     */
    public function createOrMatcher( PointcutMatcher $left, PointcutMatcher $right )
    {
        return new PointcutOrMatcher( $left, $right );
    }
}