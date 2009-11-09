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
 * @package   org\pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection;

/**
 * Proxy class representing a variable class or interface node within the
 * parsed source.
 *
 * @category  PHP
 * @package   org\pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionClassProxy extends \ReflectionClass
{
    /**
     * The type of this class.
     */
    const TYPE = __CLASS__;

    /**
     * The currently used reflection session instance.
     *
     * @var \org\pdepend\reflection\ReflectionSession
     */
    private $_session = null;

    /**
     * The qualified class/interface name.
     *
     * @var string
     */
    private $_name = null;

    /**
     * The proxied reflection class subject.
     *
     * @var \ReflectionClass
     */
    private $_proxySubject = null;

    /**
     * Constructs a new class/interface proxy.
     *
     * @param \org\pdepend\reflection\ReflectionSession $session The currently
     *        used reflection session instance that is used during the actual
     *        parsing process.
     * @param string                                    $name    Qualified name
     *        of the class/interface that is proxied by the current proxy object.
     */
    public function __construct( ReflectionSession $session, $name )
    {
        $this->_setSession( $session );
        $this->_setName( $name );
    }

    /**
     * Sets the currently used reflection session instance.
     *
     * @param \org\pdepend\reflection\ReflectionSession $session The currently
     *        used reflection session instance that is used during the actual
     *        parsing process.
     *
     * @return void
     */
    private function _setSession( ReflectionSession $session )
    {
        $this->_session = $session;
    }

    /**
     * Returns the file pathname where this interface was defined.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->_getProxySubject()->getFileName();
    }

    /**
     * Returns the qualified name of the reflected interface.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_getProxySubject()->getName();
    }

    /**
     * Sets the qualified name of the reflected interface.
     *
     * @param string $name The full qualified interface name.
     *
     * @return void
     */
    private function _setName( $name )
    {
        $this->_name = ltrim( $name, '\\' );
    }

    /**
     * Returns the short name of the class, the part without the namespace.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->_getProxySubject()->getShortName();
    }

    /**
     * Returns the namespace name of the reflected interface.
     *
     * @return string
     */
    public function getNamespaceName()
    {
        return $this->_getProxySubject()->getNamespaceName();
    }

    /**
     * Checks if this class is defined in a namespace.
     *
     * @return boolean
     */
    public function inNamespace()
    {
        return $this->_getProxySubject()->inNamespace();
    }

    /**
     * Returns the doc comment for the reflected interface or <b>false</b> when
     * no doc comment exists.
     *
     * @return string|boolean
     */
    public function getDocComment()
    {
        return $this->_getProxySubject()->getDocComment();
    }

    /**
     * Returns the class/interface modifiers
     *
     * @return integer
     */
    public function getModifiers()
    {
        return $this->_getProxySubject()->getModifiers();
    }

    /**
     * Returns <b>true</b> when the class is declared abstract or is an interface.
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->_getProxySubject()->isAbstract();
    }

    /**
     * Returns <b>true</b> when the class is declared as final.
     *
     * @return boolean
     */
    public function isFinal()
    {
        return $this->_getProxySubject()->isFinal();
    }

    /**
     * Returns <b>true</b> when the reflected interface/class is an interface,
     * in this case it means that this method always returns <b>true</b>.
     *
     * @return boolean
     */
    public function isInterface()
    {
        return $this->_getProxySubject()->isInterface();
    }

    /**
     * Checks whether the class is internal, as opposed to user-defined.
     *
     * @return boolean
     */
    public function isInternal()
    {
        return $this->_getProxySubject()->isInternal();
    }

    /**
     * Checks whether the class is user-defined, as opposed to internal.
     *
     * @return boolean
     */
    public function isUserDefined()
    {
        return $this->_getProxySubject()->isUserDefined();
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
        return $this->_getProxySubject()->isInstance( $object );
    }

    /**
     * Checks if the class is instantiable.
     *
     * @return boolean
     */
    public function isInstantiable()
    {
        return $this->_getProxySubject()->isInstantiable();
    }

    /**
     * This method will return <b>true</b> when the reflected object is a class
     * and implements the interface <b>Traversable</b>.
     *
     * @return boolean
     */
    public function isIterateable()
    {
        return $this->_getProxySubject()->isIterateable();
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
        return $this->_getProxySubject()->isSubclassOf( $class );
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
        return $this->_getProxySubject()->hasConstant( $name );
    }

    /**
     * Returns the value of a class constant with the given <b>$name</b> or
     * <b>false</b> when no constant exists for the given name.
     *
     * @param string $name The name of the searched class constant.
     *
     * @return mixed
     */
    public function getConstant( $name )
    {
        return $this->_getProxySubject()->getConstant( $name );
    }

    /**
     * Returns a key/value array with all constants and their values for the
     * currently reflected class/interface.
     *
     * @return array(string=>mixed)
     */
    public function getConstants()
    {
        return $this->_getProxySubject()->getConstants();
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
        return $this->_getProxySubject()->implementsInterface( $interface );
    }

    /**
     * Returns an array with the names of all implemented/extended interfaces.
     *
     * @return array(string)
     */
    public function getInterfaceNames()
    {
        return $this->_getProxySubject()->getInterfaceNames();
    }

    /**
     * Returns an array with all implemented/extended interfaces.
     *
     * @return array(\ReflectionClass)
     */
    public function getInterfaces()
    {
        return $this->_getProxySubject()->getInterfaces();
    }

    /**
     * Returns the parent of the reflected class or <b>false</b> when no parent
     * exists.
     *
     * @return \ReflectionClass|boolean
     */
    public function getParentClass()
    {
        return $this->_getProxySubject()->getParentClass();
    }

    /**
     * Returns the constructor of the reflected interface, what means <b>null</b>
     * because an interface has no constructor.
     *
     * @return \ReflectionMethod
     */
    public function getConstructor()
    {
        return $this->_getProxySubject()->getConstructor();
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
        return $this->_getProxySubject()->hasMethod( $name );
    }

    /**
     * Gets a <b>ReflectionMethod</b> about a method.
     *
     * @param string $name The method name to reflect.
     *
     * @return \ReflectionMethod
     */
    public function getMethod( $name )
    {
        return $this->_getProxySubject()->getMethod( $name );
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
        return $this->_getProxySubject()->getMethods( $filter );
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
        return $this->_getProxySubject()->hasProperty( $name );
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
        return $this->_getProxySubject()->getProperty( $name );
    }

    /**
     * Returns all properties declared on the current class or one of it's
     * parents.
     *
     * @param integer $filter Optional bitfield describing the modifiers a returned
     *        property must have.
     *
     * @return array(\ReflectionProperty)
     */
    public function getProperties( $filter = -1 )
    {
        return $this->_getProxySubject()->getProperties( $filter );
    }

    /**
     * Gets default properties from a class.
     *
     * @return array(mixed)
     */
    public function getDefaultProperties()
    {
        return $this->_getProxySubject()->getDefaultProperties();
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
        return $this->_getProxySubject()->getStaticPropertyValue( $name, $default );
    }

    /**
     * Get the static properties.
     *
     * @return array(string=>mixed)
     */
    public function getStaticProperties()
    {
        return $this->_getProxySubject()->getStaticProperties();
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
        return $this->_getProxySubject()->setStaticPropertyValue( $name, $value );
    }

    /**
     * Get the starting line number.
     *
     * @return integer
     */
    public function getStartLine()
    {
        return $this->_getProxySubject()->getStartLine();
    }

    /**
     * Get the ending line number.
     *
     * @return integer
     */
    public function getEndLine()
    {
        return $this->_getProxySubject()->getEndLine();
    }

    /**
     * Gets an extensions <b>\ReflectionExtension</b> object or <b>null</b>.
     *
     * @return \ReflectionExtension
     */
    public function getExtension()
    {
        return $this->_getProxySubject()->getExtension();
    }

    /**
     * Returns the name of the owning extension or <b>false</b>.
     *
     * @return string|boolean
     */
    public function getExtensionName()
    {
        return $this->_getProxySubject()->getExtensionName();
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
        $arguments = func_get_args();
        $callback  = array( $this->_getProxySubject(), 'newInstance' );

        return call_user_func_array( $callback, $arguments );
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
        return $this->_getProxySubject()->newInstanceArgs( $args );
    }

    /**
     * Returns a string representation of the underlying class or interface.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_getProxySubject()->__toString();
    }

    /**
     * Returns the proxied reflection class instance.
     *
     * @return \ReflectionClass
     */
    private function _getProxySubject()
    {
        if ( $this->_proxySubject === null )
        {
            $this->_proxySubject = $this->_session->getClass( $this->_name );
        }
        return $this->_proxySubject;
    }
}