<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\api;

use org\pdepend\reflection\parser\Parser;

require_once 'BaseTest.php';

/**
 * Abstract base test for api compatiblility between the static reflection
 * implementation and PHP's native api.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BaseCompatibilityTest extends \org\pdepend\reflection\BaseTest
{
    /**
     * Creates an internal reflection class instance.
     *
     * @param string $className Name of the searched class.
     *
     * @return \ReflectionClass
     */
    protected function createInternalClass( $className )
    {
        if ( !class_exists( $className ) && !interface_exists( $className ) )
        {
            $this->includeClass( $className );
        }
        return new \ReflectionClass( $className );
    }

    /**
     * Creates an internal reflection class instance.
     *
     * @param string $className Name of the searched class.
     *
     * @return \ReflectionClass
     */
    protected function createStaticClass( $className )
    {
        $parser  = new Parser( $this->createContext() );
        $classes = $parser->parseFile( $this->getPathnameForClass( $className ) );
        return $classes[0];
    }

    /**
     * Invokes the given method on the given object and catches a thrown exception
     * and returns the exception message. When no exception is thrown this method
     * will return <b>null</b>.
     *
     * @param object $object The context object.
     * @param string $method The method to call.
     * @param array  $args   Optional method arguments.
     *
     * @return string
     */
    protected function executeFailingMethod( $object, $method, $args = null )
    {
        try
        {
            if ( func_num_args() === 2 )
            {
                $args = array();
            }
            else
            {
                $args = func_get_args();
                array_shift( $args );
                array_shift( $args );
            }

            call_user_func_array( array( $object, $method ), $args );
        }
        catch ( \ReflectionException $e )
        {
            return $e->getMessage();
        }
        return null;
    }
}