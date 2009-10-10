<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace com\example\aop\pointcut;

/**
 * And binary matcher.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutAndMatcher extends PointcutBinaryMatcher
{
    const TYPE = __CLASS__;

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return boolean
     */
    public function match( $className, $methodName )
    {
        return (
            $this->getLeftMatcher()->match( $className, $methodName ) &&
            $this->getRightMatcher()->match( $className, $methodName )
        );
    }
}