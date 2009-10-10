<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace com\example\aop\pointcut;

use com\example\aop\interfaces\PointcutMatcher;

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
    private $_matcherLeft  = null;

    /**
     * @var PointcutMatcher
     */
    private $_matcherRight = null;

    public function __construct( PointcutMatcher $left, PointcutMatcher $right )
    {
        $this->_matcherLeft  = $left;
        $this->_matcherRight = $right;
    }

    /**
     * @return PointcutMatcher
     */
    protected function getLeftMatcher()
    {
        return $this->_matcherLeft;
    }

    /**
     * @return PointcutMatcher
     */
    protected function getRightMatcher()
    {
        return $this->_matcherRight;
    }
}