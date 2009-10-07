<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc\interfaces;

/**
 * Base interface for an argument validator.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface ArgumentValidator
{
    /**
     * @param string       $methodName
     * @param array(mixed) $arguments
     *
     * @return void
     * @throws ArgumentNotFoundException When a mandatory argument does not exist.
     * @throws ArgumentTypeException When an invalid argument type was given.
     */
    function validate( $methodName, array $arguments );
}