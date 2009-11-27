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
 * This is an empty implementation of the internal <b>\ReflectionClass</b> class.
 * It will be used for unknown code.
 *
 * @category  PHP
 * @package   org\pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class NullReflectionClass extends \ReflectionClass
{
    /**
     * The type of this class.
     */
    const TYPE = __CLASS__;

    /**
     * Qualified class or interface name.
     *
     * @var string
     */
    private $_name = null;

    /**
     * Constructs a new null reflection class instance.
     *
     * @param string $name Qualified name of the reflected class.
     */
    public function __construct( $name )
    {
        $this->_setName( $name );
    }

    /**
     * Sets the full qualified class or interface name.
     *
     * @param string $name The class or interface name.
     *
     * @return void
     */
    private function _setName( $name )
    {
        $this->_name = ltrim( $name );
    }

    /**
     * Returns the qualified name of the reflected interface.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Returns the short name of the class, the part without the namespace.
     *
     * @return string
     */
    public function getShortName()
    {
        if ( ( $pos = strrpos( $this->_name, '\\' ) ) === false )
        {
            return $this->_name;
        }
        return substr( $this->_name, $pos + 1 );
    }

    /**
     * Returns the namespace name of the reflected interface.
     *
     * @return string
     */
    public function getNamespaceName()
    {
        if ( ( $pos = strrpos( $this->_name, '\\' ) ) === false )
        {
            return '';
        }
        return substr( $this->_name, 0, $pos );
    }

    /**
     * Checks if this class is defined in a namespace.
     *
     * @return boolean
     */
    public function inNamespace()
    {
        return ( $this->getNamespaceName() !== '' );
    }

    /**
     * Returns the doc comment for the reflected interface or <b>false</b> when
     * no doc comment exists.
     *
     * @return string|boolean
     */
    public function getDocComment()
    {
        return false;
    }

    /**
     * Returns the file pathname where this interface was defined.
     *
     * @return string
     */
    public function getFileName()
    {
        return false;
    }

    /**
     * Get the ending line number.
     *
     * @return integer
     */
    public function getEndLine()
    {
        return false;
    }

    /**
     * Get the starting line number.
     *
     * @return integer
     */
    public function getStartLine()
    {
        return false;
    }

    /**
     * Returns the class/interface modifiers
     *
     * @return integer
     */
    public function getModifiers()
    {
        return 0;
    }

    /**
     * Returns the parent of the reflected class or <b>false</b> when no parent
     * exists.
     *
     * @return \ReflectionClass|boolean
     */
    public function getParentClass()
    {
        return false;
    }

    /**
     * Returns an array with the names of all implemented/extended interfaces.
     *
     * @return array(string)
     */
    public function getInterfaceNames()
    {
        return array();
    }

    /**
     * Returns an array with all implemented/extended interfaces.
     *
     * @return array(\ReflectionClass)
     */
    public function getInterfaces()
    {
        return array();
    }

    /**
     * Checks whether it implements an interface.
     *
     * @param string $interface The interface name.
     *
     * @return boolean
     */
    public function implementsInterface( $interface )
    {
        return false;
    }

    /**
     * Checks whether the specified property is defined.
     *
     * @param string $name Name of the property being checked for.
     *
     * @return boolean
     */
    public function hasProperty( $name )
    {
        return false;
    }

    /**
     * Gets a property.
     *
     * @param string $name The property name.
     *
     * @return \ReflectionProperty
     */
    public function getProperty( $name )
    {
        throw new \ReflectionException( sprintf( 'Property %s does not exist', $name ) );
    }

    /**
     * Returns an array with all properties declared/defined in this interface.
     * This value will always be an empty array.
     *
     * @param integer $filter Optional bitfield describing the modifiers a returned
     *        property must have.
     *
     * @return array(\ReflectionProperty)
     */
    public function getProperties( $filter = -1 )
    {
        return array();
    }

    /**
     * Gets the static property values.
     *
     * @param string $name    The property name.
     * @param mixed  $default Optional default value.
     *
     * @return mixed
     */
    public function getStaticPropertyValue( $name, $default = null )
    {
        if ( $default === null )
        {
            throw new \ReflectionException(
                sprintf( 'Class %s does not have a property named %s', $this->_name, $name )
            );
        }
        return $default;
    }

    /**
     * Sets static property value.
     *
     * @param string $name  The property name.
     * @param mixed  $value The new property value.
     *
     * @return void
     */
    public function setStaticPropertyValue( $name, $value )
    {
        throw new \ReflectionException( 'Method setStaticPropertyValue() is not supported' );
    }

    /**
     * Get the static properties.
     *
     * @return array(string=>mixed)
     */
    public function getStaticProperties()
    {
        return array();
    }

    /**
     * Checks whether a specific method is defined in a class.
     *
     * @param string $name Name of the method being checked for.
     *
     * @return boolean
     */
    public function hasMethod( $name )
    {
        return false;
    }

    /**
     * Gets a <b>ReflectionMethod</b> about a method.
     *
     * @param string $name The method name to reflect.
     *
     * @return \ReflectionMethod
     * @throws \ReflectionException When no methods exists for the given name.
     */
    public function getMethod( $name )
    {
        throw new \ReflectionException( sprintf( 'Method %s does not exist', $name ) );
    }

    /**
     * Returns an array with all methods within the inheritence hierarchy of this
     * class or interface.
     *
     * @param integer $filter Optional bitfield describing the modifiers a returned
     *        method must have.
     *
     * @return array(\ReflectionMethod)
     */
    public function getMethods( $filter = -1 )
    {
        return array();
    }

    /**
     * Returns the constructor of the reflected interface, what means <b>null</b>
     * because an interface has no constructor.
     *
     * @return \ReflectionClass
     */
    public function getConstructor()
    {
        return null;
    }

    /**
     * Return <b>true</b> when a constant with the given name exists in the
     * reflected interface or one of its parents.
     *
     * @param string $name Name of the search constant.
     *
     * @return boolean
     */
    public function hasConstant( $name )
    {
        return false;
    }

    /**
     * Returns the value of a constant for the given <b>$name</b> or <b>false</b>
     * when no matching constant exists.
     *
     * @param string $name Name of the searched constant.
     *
     * @return mixed
     */
    public function getConstant( $name )
    {
        return false;
    }

    /**
     * Returns an array with constants defined in this or one of its parent
     * classes.
     *
     * @return array(string=>mixed)
     */
    public function getConstants()
    {
        return array();
    }

    /**
     * Gets default properties from a class.
     *
     * @return array(mixed)
     */
    public function getDefaultProperties()
    {
        return array();
    }

    /**
     * Gets an extensions <b>\ReflectionExtension</b> object or <b>null</b>.
     *
     * @return \ReflectionExtension
     */
    public function getExtension()
    {
        return null;
    }

    /**
     * Returns the name of the owning extension or <b>false</b>.
     *
     * @return string|boolean
     */
    public function getExtensionName()
    {
        return false;
    }

    /**
     * Returns <b>true</b> when the class is declared abstract or is an interface.
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return false;
    }

    /**
     * Returns <b>true</b> when the class is declared as final.
     *
     * @return boolean
     */
    public function isFinal()
    {
        return false;
    }

    /**
     * Checks if a class is an instance of an object.
     *
     * @param object $object The object to check.
     *
     * @return boolean
     */
    public function isInstance( $object )
    {
        return ( $object instanceof $this->_name );
    }

    /**
     * Checks if the class is instantiable.
     *
     * @return boolean
     */
    public function isInstantiable()
    {
        return false;
    }

    /**
     * Checks whether the class is internal, as opposed to user-defined.
     *
     * @return boolean
     */
    public function isInternal()
    {
        return false;
    }

    /**
     * Returns <b>true</b> when the reflected interface/class is an interface,
     * in this case it means that this method always returns <b>true</b>.
     *
     * @return boolean
     */
    public function isInterface()
    {
        return false;
    }

    /**
     * This method will return <b>true</b> when the reflected object is a class
     * and implements the interface <b>Traversable</b>.
     *
     * @return boolean
     */
    public function isIterateable()
    {
        return false;
    }

    /**
     * Checks that the reflected interface is a child of the given class name.
     *
     * @param string $class Name of the searched class.
     *
     * @return boolean
     */
    public function isSubclassOf( $class )
    {
        return false;
    }

    /**
     * Checks whether the class is user-defined, as opposed to internal.
     *
     * @return boolean
     */
    public function isUserDefined()
    {
        return false;
    }

    /**
     * Creates an instance of the context class.
     *
     * @param mixed $args Accepts a variable number of arguments which are
     *                    passed to the function much like call_user_func().
     *
     * @return object
     */
    public function newInstance( $args )
    {
        throw new \ReflectionException( 'Method newInstance() is not supported' );
    }

    /**
     * Creates an instance of the context class.
     *
     * @param array(mixed) $args Arguments which are passed to the constructor
     *                           much like call_user_func_array().
     *
     * @return object
     */
    public function newInstanceArgs( array $args = array() )
    {
        throw new \ReflectionException( 'Method newInstanceArgs() is not supported' );
    }

    /**
     * Returns a string representation of this reflection instance.
     *
     * @return string
     */
    public function __toString()
    {
        return 'Class [ 42 ]';
    }
}