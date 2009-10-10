<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc\exceptions;

/**
 * Exception that will be thrown when when a configured class could not be found.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ClassNotFoundException extends Exception
{
    /**
     * @param string $className
     */
    public function __construct( $className )
    {
        parent::__construct(
            sprintf( 'Cannot locate source file for class: %s.', $className  )
        );
    }
}