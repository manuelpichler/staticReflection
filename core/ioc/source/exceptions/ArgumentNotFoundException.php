<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc\exceptions;

/**
 * Exception that will be thrown when a mandatory argument does not exist.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ArgumentNotFoundException extends Exception
{
    /**
     * @param ReflectionParameter $parameter
     */
    public function __construct( \ReflectionParameter $parameter )
    {
        parent::__construct(
            sprintf( 'Missing mandatory argument $%s.', $parameter->getName() )
        );
    }
}