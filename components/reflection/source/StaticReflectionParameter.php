<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

/**
 * Static parameter implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionParameter extends \ReflectionParameter
{
    const TYPE = __CLASS__;

    /**
     * Method where this parameter is used.
     *
     * @var \ReflectionMethod
     */
    private $_declaringMethod = null;

    public function __construct()
    {

    }

    public function getName()
    {
        
    }

    public function getPosition()
    {
        
    }

    public function allowsNull()
    {
        
    }

    public function getClass()
    {

    }

    public function getDeclaringClass()
    {
        return $this->_declaringMethod->getDeclaringClass();
    }

    /**
     * Returns the function where this parameter was declared.
     *
     * @return \ReflectionFunction
     */
    public function getDeclaringFunction()
    {
        return $this->_declaringMethod;
    }

    /**
     * Initializes the declaring method instance.
     *
     * @param \ReflectionMethod $declaringMethod The declaring method.
     *
     * @return void
     * @access private
     */
    public function initDeclaringMethod( \ReflectionMethod $declaringMethod )
    {
        if ( $this->_declaringMethod === null )
        {
            $this->_declaringMethod = $declaringMethod;
        }
        else
        {
            throw new \LogicException( 'Property declaringMethod already set' );
        }
    }

    public function getDefaultValue()
    {

    }

    public function isArray()
    {

    }

    public function isDefaultValueAvailable()
    {

    }

    public function isOptional()
    {

    }

    public function isPassedByReference()
    {
        
    }

    public function __toString()
    {
        
    }
}