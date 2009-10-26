<?php
/**
 * This file is part of the static reflection component.
 *
 * PHP Version 5
 *
 * Copyright (c) 2008-2009, Manuel Pichler <mapi@pdepend.org>.
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
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
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