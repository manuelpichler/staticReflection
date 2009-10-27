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
 * Static method implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionMethod extends \ReflectionMethod
{
    const TYPE = __CLASS__;

    /**
     * @var string
     */
    private $_name = null;

    /**
     * @var string
     */
    private $_docComment = false;

    /**
     * @var integer
     */
    private $_modifiers = 0;

    /**
     * Flags the reflected method as returns or not returns a value by reference.
     *
     * @var boolean
     */
    private $_returnsReference = false;

    /**
     * The declaring class.
     *
     * @var \ReflectionClass
     */
    private $_declaringClass = null;

    /**
     * Parameters declared for the reflected method.
     *
     * @var array(\ReflectionParameter)
     */
    private $_parameters = null;

    /**
     * The start line number of the reflected method.
     *
     * @var integer
     */
    private $_startLine = -1;

    /**
     * The end line number of the reflected method.
     *
     * @var integer
     */
    private $_endLine = -1;

    /**
     * @param string  $name
     * @param string  $docComment
     * @param integer $modifiers
     */
    public function __construct( $name, $docComment, $modifiers )
    {
        $this->_setName( $name );
        $this->_setModifiers( $modifiers );
        $this->_setDocComment( $docComment );
    }

    /**
     * Returns the name of the reflected method.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the reflected method.
     *
     * @param string $name Name of the reflected method.
     *
     * @return void
     */
    private function _setName( $name )
    {
        $this->_name = $name;
    }

    /**
     * Returns the method's short name.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->_name;
    }

    /**
     * Get the namespace name where the class is defined.
     *
     * @return string
     */
    public function getNamespaceName()
    {
        return '';
    }

    /**
     * Returns <b>true</b> when the reflected function is declared in a namespace,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function inNamespace()
    {
        return false;
    }

    /**
     * Returns the doc comment of the reflected method or <b>false</b> when no
     * comment was found.
     *
     * @return string|boolean
     */
    public function getDocComment()
    {
        return $this->_docComment;
    }

    /**
     * Sets the doc comment of the reflected method.
     *
     * @param string $docComment Doc comment for the reflected method.
     *
     * @return void
     */
    private function _setDocComment( $docComment )
    {
        if ( trim( $docComment ) !== '' )
        {
            $this->_docComment = $docComment;
        }
    }

    /**
     * Returns the modifiers of the reflected method.
     *
     * @return integer
     */
    public function getModifiers()
    {
        return $this->_modifiers;
    }

    /**
     * Sets and validates the modifiers of the reflected method.
     *
     * @param integer $modifiers The modifiers for the reflected method.
     *
     * @return void
     * @access private
     */
    public function _setModifiers( $modifiers )
    {
        $expected = self::IS_PRIVATE | self::IS_PROTECTED | self::IS_PUBLIC
                  | self::IS_STATIC  | self::IS_ABSTRACT  | self::IS_FINAL;

        if ( ( $modifiers & ~$expected ) !== 0 )
        {
            throw new \ReflectionException( 'Invalid method modifier given.' );
        }
        $this->_modifiers = $modifiers;
    }

    /**
     * Returns the pathname where the reflected method was declared.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->getDeclaringClass()->getFileName();
    }

    /**
     * Returns <b>true</b> when the reflected method is the ctor of the parent
     * class instance.
     *
     * @return boolean
     */
    public function isConstructor()
    {
        if ( $this->getDeclaringClass()->isInterface() )
        {
            return false;
        }
        else if ( strcasecmp( $this->getName(), '__construct' ) === 0 )
        {
            return true;
        }
        else if ( $this->getDeclaringClass()->hasMethod( '__construct' ) )
        {
            return false;
        }
        return ( strcasecmp( $this->getName(), $this->getDeclaringClass()->getShortName() ) === 0 );
    }

    /**
     * Returns <b>true</b> when the reflected method is the dtor of the parent
     * class instance.
     *
     * @return boolean
     */
    public function isDestructor()
    {
        if ( $this->getDeclaringClass()->isInterface() )
        {
            return false;
        }
        return ( strcasecmp( $this->getName(), '__destruct' ) === 0 );
    }

    /**
     * Returns <b>true</b> when the reflected method is declared as abstract.
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return ( ( $this->_modifiers & self::IS_ABSTRACT ) === self::IS_ABSTRACT );
    }

    /**
     * Returns <b>true</b> when the reflected method is declared as static.
     *
     * @return boolean
     */
    public function isStatic()
    {
        return ( ( $this->_modifiers & self::IS_STATIC ) === self::IS_STATIC );
    }

    /**
     * Returns <b>true</b> when the reflected method is declared as final.
     *
     * @return boolean
     */
    public function isFinal()
    {
        return ( ( $this->_modifiers & self::IS_FINAL ) === self::IS_FINAL );
    }

    /**
     * Returns <b>true</b> when the reflected method is declared as private.
     *
     * @return boolean
     */
    public function isPrivate()
    {
        return ( ( $this->_modifiers & self::IS_PRIVATE ) === self::IS_PRIVATE );
    }

    /**
     * Returns <b>true</b> when the reflected method is declared as protected.
     *
     * @return boolean
     */
    public function isProtected()
    {
        return ( ( $this->_modifiers & self::IS_PROTECTED ) === self::IS_PROTECTED );
    }

    /**
     * Returns <b>true</b> when the reflected method is declared as public.
     *
     * @return boolean
     */
    public function isPublic()
    {
        return ( ( $this->_modifiers & self::IS_PUBLIC ) === self::IS_PUBLIC );
    }

    /**
     * Returns <b>true</b> when the reflected method/function is flagged as
     * deprecated.
     *
     * @return boolean
     */
    public function isDeprecated()
    {
        return false;
    }

    /**
     * Returns <b>true</b> when the reflected method is declared by an internal
     * class/interface, or <b>false</b> when it is user-defined.
     *
     * @return boolean
     */
    public function isInternal()
    {
        return false;
    }

    /**
     * Returns <b>true</b> when the reflected method is user-defined, otherwise
     * this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isUserDefined()
    {
        return true;
    }

    /**
     * Returns <b>true</b> when the reflected method/function is a closure,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isClosure()
    {
        return false;
    }

    /**
     * Gets the declaring class.
     *
     * @return \ReflectionClass
     */
    public function getDeclaringClass()
    {
        return $this->_declaringClass;
    }

    /**
     * Sets the <b>ReflectionClass</b> where the reflected method is declared.
     *
     * @param \ReflectionClass $declaringClass The class where the reflected
     *        method is declared.
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
            throw new \LogicException( 'Declaring class already set' );
        }
    }

    /**
     * Returns the start line number of the reflected method's declaration.
     *
     * @return integer
     */
    public function getStartLine()
    {
        return $this->_startLine;
    }

    /**
     * Initializes the start line number where the method's declaration starts.
     *
     * @param integer $startLine The methods start line number.
     *
     * @return void
     * @access private
     */
    public function initStartLine( $startLine )
    {
        if ( $this->_startLine === -1 )
        {
            $this->_startLine = $startLine;
        }
        else
        {
            throw new \LogicException( 'Property startLine already set' );
        }
    }

    /**
     * Returns the end line number of the reflected method's declaration.
     *
     * @return integer
     */
    public function getEndLine()
    {
        return $this->_endLine;
    }

    /**
     * Initializes the end line number where the method's declaration ends.
     *
     * @param integer $endLine The methods end line number.
     *
     * @return void
     * @access private
     */
    public function initEndLine( $endLine )
    {
        if ( $this->_endLine === -1 )
        {
            $this->_endLine = $endLine;
        }
        else
        {
            throw new \LogicException( 'Property endLine already set' );
        }
    }

    /**
     * Returns an <b>array</b> with all parameters of the reflected method.
     *
     * @return array(\ReflectionParameter)
     */
    public function getParameters()
    {
        return (array) $this->_parameters;
    }

    /**
     * Returns the total number of parameters for the reflected method.
     *
     * @return integer
     */
    public function getNumberOfParameters()
    {
        return count( $this->getParameters() );
    }

    public function getNumberOfRequiredParameters()
    {

    }

    /**
     * Initializes the parameters declared for the reflected method.
     *
     * @param array(\org\pdepend\reflection\api\StaticReflectionParameter) $parameters
     *        Allowed parameters for the reflected method.
     *
     * @return void
     * @access private
     */
    public function initParameters( array $parameters )
    {
        if ( $this->_parameters === null )
        {
            $this->_initParameters( $parameters );
        }
        else
        {
            throw new \LogicException( 'Property parameters already set' );
        }
    }

    /**
     * Initializes the parameters declared for the reflected method.
     *
     * @param array(\org\pdepend\reflection\api\StaticReflectionParameter) $parameters
     *        Allowed parameters for the reflected method.
     *
     * @return void
     */
    private function _initParameters( array $parameters )
    {
        $this->_parameters = array();
        foreach ( $parameters as $parameter )
        {
            $parameter->initDeclaringMethod( $this );
            $this->_parameters[] = $parameter;
        }
    }

    /**
     * Returns <b>true</b> when the reflected method returns its return value
     * by reference. Otherwise the return value of this method will be
     * <b>false</b>.
     *
     * @return boolean
     */
    public function returnsReference()
    {
        return $this->_returnsReference;
    }

    /**
     * A call to this method flags the reflected method as returns by reference.
     *
     * @return void
     * @access private
     */
    public function initReturnsReference()
    {
        if ( $this->_returnsReference === false )
        {
            $this->_returnsReference = true;
        }
        else
        {
            throw new \LogicException( 'Property returnsReference already set' );
        }
    }

    public function getStaticVariables()
    {
        
    }

    /**
     * Returns a <b>\ReflectionExtension</b> of the extension where the reflected
     * method was declared. If the method is not part of an extension this
     * method will return <b>null</b>.
     *
     * @return \ReflectionExtension
     */
    public function getExtension()
    {
        return null;
    }

    /**
     * Returns the name of the extension where the reflected method was
     * declared. When the reflected method does not belong to an extension this
     * method will return <b>false</b>.
     *
     * @return string|boolean
     */
    public function getExtensionName()
    {
        return false;
    }

    /**
     * Will invoke the reflected method on the given <b>$object</b>.
     *
     * @param object $object The context object instance.
     * @param mixed  $args   Variable list of method arguments.
     *
     * @return void
     */
    public function invoke( $object, $args = null )
    {
        throw new \ReflectionException( 'Method invoke() is not supported' );
    }

    /**
     * Will invoke the reflected method on the given <b>$object</b>.
     *
     * @param object       $object The context object instance.
     * @param array(mixed) $args   Array with method arguments
     *
     * @return void
     */
    public function invokeArgs( $object, array $args = array() )
    {
        throw new \ReflectionException( 'Method invokeArgs() is not supported' );
    }

    /**
     * Returns the prototype of the context function.
     */
    public function getPrototype()
    {
        
    }

    /**
     * Returns a string representation of the reflected method.
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}