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
     * @param JoinPoint $joinPoint
     *
     * @return boolean
     */
    public function match( JoinPoint $joinPoint )
    {
        $pattern = sprintf( 
            '(^%s %s::%s$)',
            $this->_prepareRegexp( $this->_visibility === '' ? '*' : $this->_visibility ),
            $this->_prepareRegexp( $this->_prepareNamespaceDelimiter( $this->_className ) ),
            $this->_prepareRegexp( $this->_methodName )
        );
        $subject = sprintf( 
            '%s %s::%s',
            $joinPoint->getVisibility(),
            $this->_prepareNamespaceDelimiter( $joinPoint->getClassName() ),
            $joinPoint->getMethodName()
        );

        return ( preg_match( $pattern, $subject ) === 1 );
    }

    private function _prepareRegexp( $pattern )
    {
        return str_replace( '\\*', '.*', preg_quote( $pattern ) );
    }

    private function _prepareNamespaceDelimiter( $name )
    {
        return strtr( $name, '\\', '/' );
    }
}