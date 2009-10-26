<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\api;

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
     * Name of the reflected parameter.
     *
     * @var string
     */
    private $_name = null;

    /**
     * Parameter position in the argument list.
     *
     * @var integer
     */
    private $_position = 0;

    /**
     * Method where this parameter is used.
     *
     * @var \ReflectionMethod
     */
    private $_declaringMethod = null;

    /**
     * Constructs a new parameter instance.
     *
     * @param string  $name     Name of the parameter.
     * @param integer $position Position in argument list.
     */
    public function __construct( $name, $position )
    {
        $this->_setName( $name );
        $this->_setPosition( $position );
    }

    /**
     * Sets the name of the reflected parameter.
     *
     * @param string $name The parameter name.
     *
     * @return void
     */
    private function _setName( $name )
    {
        $this->_name = ltrim( $name, '$' );
    }

    /**
     * Sets the argument list position of the reflected parameter.
     *
     * @param integer $position The parameter position.
     *
     * @return void
     */
    private function _setPosition( $position )
    {
        $this->_position = (int) $position;
    }

    /**
     * Gets the name of the reflected parameter.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Gets the argument list position of the reflected parameter.
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->_position;
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