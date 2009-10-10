<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace de\buzz2ee\aop\interfaces;

/**
 * Base interface for a pointcut matcher.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface PointcutMatcher
{
    /**
     * @param string $className
     * @param string $methodName
     *
     * @return boolean
     */
    function match( $className, $methodName );
}