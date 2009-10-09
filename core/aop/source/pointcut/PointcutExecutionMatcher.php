<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace com\example\aop\pointcut;

use com\example\aop\interfaces\PointcutMatcher;

/**
 * Execution pointcut matcher implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutExecutionMatcher implements PointcutMatcher
{
    const TYPE = __CLASS__;

    /**
     * @var string
     */
    private $_visibility = null;

    /**
     * @var string
     */
    private $_className = null;

    /**
     * @var string
     */
    private $_methodName = null;

    /**
     * @param string $className
     * @param string $methodName
     * @param string $visibility
     */
    public function __construct( $className, $methodName, $visibility )
    {
        $this->_className  = $className;
        $this->_methodName = $methodName;
        $this->_visibility = $visibility;
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return boolean
     */
    public function match( $className, $methodName )
    {

    }
}