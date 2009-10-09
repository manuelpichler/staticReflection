<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\aop\exceptions;

/**
 * This type of exception will be thrown when an invalid pointcut expression
 * was found.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class InvalidPointcutExpressionException extends Exception
{
    /**
     * @param string $expression
     */
    public function __construct( $expression )
    {
        parent::__construct( sprintf( 'Invalid pointcut expression: %s', $expression ) );
    }
}