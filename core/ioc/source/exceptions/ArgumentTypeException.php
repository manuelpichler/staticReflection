<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc\exceptions;

/**
 * Exception that will be thrown when an invalid argument type was given.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ArgumentTypeException extends Exception
{
    /**
     * @param string $expectedType
     * @param string $actualType
     */
    public function __construct( $expectedType, $actualType )
    {
        parent::__construct(
            sprintf(
                'Invalid argument of type: %s given, expected was: %s',
                $actualType,
                $expectedType
            )
        );
    }
}