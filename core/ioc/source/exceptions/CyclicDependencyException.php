<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc\exceptions;

/**
 * Exception that will be thrown when a cyclic dependency was found.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class CyclicDependencyException extends Exception
{
    public function __construct()
    {
        parent::__construct( 'Cyclic dependency detected.' );
    }
}