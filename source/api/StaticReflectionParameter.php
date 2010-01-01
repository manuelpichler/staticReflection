<?php
/**
 * This file is part of the static reflection component.
 *
 * PHP Version 5
 *
 * Copyright (c) 2009-2010, Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  PHP
 * @package   org\pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection\api;

/**
 * Static parameter implementation.
 *
 * @category  PHP
 * @package   org\pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
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
     * Is the reflected parameter optional or mandatory?
     *
     * @var boolean
     */
    private $_optional = false;

    /**
     * Is the reflected parameter declared as array parameter?
     *
     * @var boolean
     */
    private $_array = false;

    /**
     * The class/interface type-hint for the reflected parameter or <b>null</b>.
     *
     * @var \ReflectionClass
     */
    private $_class = null;

    /**
     * Is the reflected parameter passed by reference?
     *
     * @var boolean
     */
    private $_passedByReference = false;

    /**
     * Optional value object that holds the default parameter value.
     *
     * @var \org\pdepend\reflection\api\StaticReflectionValue
     */
    private $_StaticReflectionValue = null;

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

    /**
     * Returns <b>true</b> when it is possible to pass the value <b>null</b> for
     * the reflected parameter.
     *
     * @return boolean
     */
    public function allowsNull()
    {
        if ( $this->_hasTypeHint() )
        {
            return ( $this->isDefaultValueAvailable() && is_null( $this->_StaticReflectionValue->getData() ) );
        }
        return true;
    }

    /**
     * Returns a <b>\ReflectionClass</b> instance representing the type of the
     * reflected parameter or <b>null</b> when no type type-hint is used for this
     * parameter.
     *
     * @return \ReflectionClass
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     * Returns the class where the parent method of the reflected parameter is
     * declared.
     *
     * @return \ReflectionClass
     */
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

    /**
     * Returns <b>true</b> when a default value is available for the reflected
     * parameter.
     *
     * @return boolean
     */
    public function isDefaultValueAvailable()
    {
        return is_object( $this->_StaticReflectionValue );
    }

    /**
     * Returns the default value of the reflected parameter or throws an
     * exception when the parameter has no default value.
     *
     * @return mixed
     * @throws \ReflectionException When the reflected parameter has no default.
     */
    public function getDefaultValue()
    {
        if ( $this->isDefaultValueAvailable() )
        {
            return $this->_StaticReflectionValue->getData();
        }
        throw new \ReflectionException( 'Parameter is not optional' );
    }

    /**
     * Initializes the parameter default value when available.
     *
     * @param \org\pdepend\reflection\api\StaticReflectionValue $StaticReflectionValue The defined
     *        parameter default value or <b>null</b> when not available.
     *
     * @return void
     * @access private
     */
    public function initStaticReflectionValue( StaticReflectionValue $StaticReflectionValue = null )
    {
        if ( $this->isDefaultValueAvailable() )
        {
            throw new \LogicException( 'Property StaticReflectionValue already set' );
        }
        else
        {
            $this->_StaticReflectionValue = $StaticReflectionValue;
        }
    }

    /**
     * Returns <b>true</b> when the reflected parameter was declared with an
     * <b>array</b> type-hint.
     *
     * @return boolean
     */
    public function isArray()
    {
        return $this->_array;
    }

    /**
     * Returns <b>true</b> when the reflected parameter is optional.
     *
     * @return boolean
     */
    public function isOptional()
    {
        if ( $this->isDefaultValueAvailable() )
        {
            return $this->_isOptionalByFollowingParameters();
        }
        return false;
    }

    /**
     * Returns <b>true</b> when this parameter is not implicit mandatory because
     * one of the following parameters is mandatory.
     *
     * @return boolean
     */
    private function _isOptionalByFollowingParameters()
    {
        $behindCurrentParameter = false;
        foreach ( $this->_declaringMethod->getParameters() as $parameter )
        {
            if ( $parameter === $this )
            {
                $behindCurrentParameter = true;
            }
            else if ( $behindCurrentParameter && !$parameter->isDefaultValueAvailable() )
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns <b>true</b> when the reflected parameter is passed by reference.
     *
     * @return boolean
     */
    public function isPassedByReference()
    {
        return $this->_passedByReference;
    }

    /**
     * Sets the passed by reference flag to <b>true</b>.
     *
     * @return void
     * @access private
     */
    public function initPassedByReference()
    {
        if ( $this->_passedByReference === false )
        {
            $this->_passedByReference = true;
        }
        else
        {
            throw new \LogicException( 'Property passedByReference already set' );
        }
    }

    /**
     * Initializes the type hint for the reflected parameter.
     *
     * @param \ReflectionClass|boolean $classOrBoolean Type hint for the
     *        reflected method. A boolean <b>true</b> value means an array
     *        type-hint, while a reflection class instance means a type type-hint.
     *
     * @return void
     * @access private
     */
    public function initTypeHint( $classOrBoolean )
    {
        if ( $this->_hasTypeHint() )
        {
            throw new \LogicException( 'Property typeHint already set' );
        }
        else
        {
            $this->_initTypeHint( $classOrBoolean );
        }
    }

    /**
     * Returns <b>true</b> when a type hint exists for the reflected parameter.
     *
     * @return boolean
     */
    private function _hasTypeHint()
    {
        return ( $this->_array || is_object( $this->_class ) );
    }

    /**
     * Initializes the type hint for the reflected parameter.
     *
     * @param \ReflectionClass|boolean $classOrBoolean Type hint for the
     *        reflected method. A boolean <b>true</b> value means an array
     *        type-hint, while a reflection class instance means a type type-hint.
     *
     * @return void
     * @access private
     */
    private function _initTypeHint( $classOrBoolean )
    {
        if ( $classOrBoolean instanceof \ReflectionClass )
        {
            $this->_class = $classOrBoolean;
        }
        else if ( $classOrBoolean === true )
        {
            $this->_array = true;
        }
        else
        {
            throw new \LogicException( 'Invalid type hint value given' );
        }
    }

    /**
     * Returns a string representation of this property.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%sParameter #%d [ <%s> %s$%s%s ]',
            ( func_num_args() === 0 ? '' : func_get_arg( 0 ) ),
            $this->getPosition(),
            $this->isOptional() ? 'optional' : 'required',
            $this->_getOptionalTypeHint(),
            $this->getName(),
            $this->_getOptionalStaticReflectionValue()
        );
    }

    /**
     * Returns the string representation of the parameter's type hint or an
     * empty string when no type hint exists.
     *
     * @return string
     */
    private function _getOptionalTypeHint()
    {
        if ( is_object( $this->getClass() ) )
        {
            return $this->getClass()->getName() . ( $this->allowsNull() ? ' or NULL ' : ' ' );
        }
        else if ( $this->isArray() )
        {
            return 'array ' . ( $this->allowsNull() ? 'or NULL ' : '' );
        }
        return '';
    }

    /**
     * Returns the string representation of the parameter's default value or an
     * empty string when no default value exists.
     * 
     * @return string
     */
    private function _getOptionalStaticReflectionValue()
    {
        if ( $this->isDefaultValueAvailable() )
        {
            return ' = ' . $this->_StaticReflectionValue->__toString();
        }
        return '';
    }
}