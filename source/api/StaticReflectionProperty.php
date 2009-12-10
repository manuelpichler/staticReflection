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
 * Static property implementation.
 *
 * @category  PHP
 * @package   org\pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org
 */
class StaticReflectionProperty extends \ReflectionProperty
{
    const TYPE = __CLASS__;

    /**
     * @var string
     */
    private $_name = null;

    /**
     * @var string|boolean
     */
    private $_docComment = false;

    /**
     * @var integer
     */
    private $_modifiers = 0;

    /**
     * @var \ReflectionClass
     */
    private $_declaringClass = null;

    /**
     * Default property value.
     *
     * @var mixed
     */
    private $_value = null;

    /**
     * Does the property declaration define a default value?
     *
     * @var boolean
     */
    private $_initialized = false;

    /**
     * Constructs a new reflection property instance.
     *
     * @param string  $name       Name of the current property.
     * @param string  $docComment Optional doc comment for this property.
     * @param integer $modifiers  Modifier for this property.
     */
    public function __construct( $name, $docComment, $modifiers )
    {
        $this->setName( $name );
        $this->setModifiers( $modifiers );
        $this->setDocComment( $docComment );
    }

    /**
     * Returns the class where the reflected property was declared.
     *
     * @return \ReflectionClass
     */
    public function getDeclaringClass()
    {
        return $this->_declaringClass;
    }

    /**
     * Sets the declaring class for the context property. Note that this method
     * is only for internal usage.
     *
     * @param \ReflectionClass $declaringClass The declaring class.
     *
     * @return void
     * @access private
     */
    public function initDeclaringClass( \ReflectionClass $declaringClass )
    {
        if ( $this->_declaringClass === null )
        {
            $this->_declaringClass = $declaringClass;
        }
        else
        {
            throw new \LogicException( 'Property declaringClass already set' );
        }
    }

    /**
     * Returns the name of the reflected property.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the reflected property. A leading <b>$</b> will be
     * stripped from the property name.
     *
     * @param string $name The property name.
     *
     * @return void
     */
    protected function setName( $name )
    {
        $this->_name = ltrim( $name, '$' );
    }

    /**
     * Returns the doc comment of the reflected property or <b>false</b> when
     * no doc comment exists.
     *
     * @return string|boolean
     */
    public function getDocComment()
    {
        return $this->_docComment;
    }

    /**
     * Sets the doc comment for the reflected property. This method will only
     * set the doc comment attribute when the given value is not empty.
     *
     * @param string $docComment The doc comment for the reflected property.
     *
     * @return void
     */
    protected function setDocComment( $docComment )
    {
        if ( ( $comment = trim( $docComment ) ) !== '' )
        {
            $this->_docComment = $comment;
        }
    }

    /**
     * Returns a numeric representation of the modifiers for the reflected
     * property.
     * 
     * @return integer
     */
    public function getModifiers()
    {
        return $this->_modifiers;
    }

    /**
     * Sets the numeric representation of the modifiers for the reflected
     * property.
     *
     * @param integer $modifiers Numeric representation of the modifiers.
     *
     * @return void
     */
    protected function setModifiers( $modifiers )
    {
        $expected = self::IS_PRIVATE | self::IS_PROTECTED | self::IS_PUBLIC | self::IS_STATIC;

        if ( ( $modifiers & ~$expected ) !== 0 )
        {
            throw new \ReflectionException( 'Invalid property modifier given.' );
        }
        $this->_modifiers = $modifiers;
    }

    /**
     * Returns <b>true</b> when the reflected property was declared as static,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isStatic()
    {
        return ( ( $this->_modifiers & self::IS_STATIC ) === self::IS_STATIC );
    }

    /**
     * Returns <b>true</b> when the reflected property was declared as private,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isPrivate()
    {
        return ( ( $this->_modifiers & self::IS_PRIVATE ) === self::IS_PRIVATE );
    }

    /**
     * Returns <b>true</b> when the reflected property was declared as protected,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isProtected()
    {
        return ( ( $this->_modifiers & self::IS_PROTECTED ) === self::IS_PROTECTED );
    }

    /**
     * Returns <b>true</b> when the reflected property was declared as public,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isPublic()
    {
        return ( ( $this->_modifiers & self::IS_PUBLIC ) === self::IS_PUBLIC );
    }

    /**
     * Checks if the reflected property is default and declared at compile time
     * and returns <b>true</b>, otherwise the returned value is <b>false</b> if
     * it was created at run-time.
     *
     * @return boolean
     */
    public function isDefault()
    {
        return true;
    }

    /**
     * Returns the value of the context property.
     *
     * @param object $object The object being reflected.
     *
     * @return mixed
     */
    public function getValue( $object = null )
    {
        if ( $object === null )
        {
            return $this->_value;
        }
        throw new \ReflectionException( 'Method getValue() is not supported' );
    }

    /**
     * Sets the value of the context property.
     *
     * @param object $object The object being reflected.
     * @param mixed  $value  The new property value.
     *
     * @return void
     */
    public function setValue( $object, $value = null )
    {
        throw new \ReflectionException( 'Method setValue() is not supported' );
    }

    /**
     * Initializes the default value of the reflected property.
     *
     * @param \org\pdepend\reflection\api\StaticReflectionValue $value Default property value.
     *
     * @return void
     * @access private
     */
    public function initValue( StaticReflectionValue $value = null )
    {
        if ( $this->_initialized === false )
        {
            $this->_initialized = true;
            if ( is_object( $value ) )
            {
                $this->_value = $value->getData();
            }
        }
        else
        {
            throw new \LogicException( 'Property value already set' );
        }
    }

    /**
     * Sets a property to be accessible. For example, it may allow protected and
     * private properties to be accessed.
     *
     * @param boolean $accessible <b>true</b> to allow accessibility, or <b>false</b>.
     *
     * @return void
     */
    public function setAccessible( $accessible )
    {
        throw new \ReflectionException( 'Method setAccessible() is not supported' );
    }

    /**
     * Returns a string representation of this property.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%sProperty [ %s%s%s%s%s$%s ]',
            ( func_num_args() === 0 ? '' : func_get_arg( 0 ) ),
            $this->isStatic() ? '' : '<default> ',
            $this->isPublic() ? 'public ' : '',
            $this->isPrivate() ? 'private ' : '',
            $this->isProtected() ? 'protected ' : '',
            $this->isStatic() ? 'static ' : '',
            $this->getName()
        );
    }
}
