<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace de\buzz2ee\aop\pointcut;

use de\buzz2ee\aop\interfaces\PointcutMatcher;

/**
 * Negating pointcut matcher.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutNotMatcher implements PointcutMatcher
{
    const TYPE = __CLASS__;

    /**
     * @var PointcutMatcher
     */
    private $_matcher = null;

    /**
     * @param PointcutMatcher $matcher
     */
    public function __construct( PointcutMatcher $matcher )
    {
        $this->_matcher = $matcher;
    }
    /**
     * @param string $className
     * @param string $methodName
     *
     * @return boolean
     */
    public function match( $className, $methodName )
    {
        return !$this->_matcher->match( $className, $methodName );
    }
}