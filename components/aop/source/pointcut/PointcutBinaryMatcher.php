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
 * Abstract base implementation of a binary matcher.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class PointcutBinaryMatcher implements PointcutMatcher
{
    /**
     * @var PointcutMatcher
     */
    private $_left  = null;

    /**
     * @var PointcutMatcher
     */
    private $_right = null;

    public function __construct( PointcutMatcher $left, PointcutMatcher $right )
    {
        $this->_left  = $left;
        $this->_right = $right;
    }

    /**
     * @param JoinPoint $joinPoint
     *
     * @return boolean
     */
    protected function matchLeft( JoinPoint $joinPoint )
    {
        return $this->_left->match( $joinPoint );
    }

    /**
     * @param JoinPoint $joinPoint
     *
     * @return boolean
     */
    protected function matchRight( JoinPoint $joinPoint )
    {
        return $this->_right->match( $joinPoint );
    }
}