<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace de\buzz2ee\aop\pointcut;

use de\buzz2ee\aop\interfaces\JoinPoint;
use de\buzz2ee\aop\interfaces\PointcutMatcher;

/**
 * Named pointcut reference matcher implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutNamedMatcher implements PointcutMatcher
{
    const TYPE = __CLASS__;

    /**
     * @param JoinPoint $joinPoint
     *
     * @return boolean
     */
    public function match( JoinPoint $joinPoint )
    {

    }
}