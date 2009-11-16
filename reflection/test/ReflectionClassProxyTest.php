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

require_once 'BaseTest.php';

/**
 * Test case for the reflection class proxy.
 *
 * @category  PHP
 * @package   org\pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionClassProxyTest extends BaseTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetFileNameDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getFileName' );
        $proxy->getFileName();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetNameDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getName' );
        $proxy->getName();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetShortNameDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getShortName' );
        $proxy->getShortName();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetNamespaceNameDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getNamespaceName' );
        $proxy->getNamespaceName();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyInNamespaceDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'inNamespace' );
        $proxy->inNamespace();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetDocCommentDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getDocComment' );
        $proxy->getDocComment();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetModifiersDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getModifiers' );
        $proxy->getModifiers();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsAbstractDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isAbstract' );
        $proxy->isAbstract();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsFinalDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isFinal' );
        $proxy->isFinal();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsInterfaceDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isInterface' );
        $proxy->isInterface();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsInternalDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isInternal' );
        $proxy->isInternal();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsUserDefinedDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isUserDefined' );
        $proxy->isUserDefined();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsInstanceDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isInstance' );
        $proxy->isInstance( $this );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsInstantiableDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isInstantiable' );
        $proxy->isInstantiable();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsIterateableDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isIterateable' );
        $proxy->isIterateable();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyIsSubclassOfDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'isSubclassOf' );
        $proxy->isSubclassOf( '\stdClass' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyHasConstantDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'hasConstant' );
        $proxy->hasConstant( 'FOO' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetConstantDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getConstant' );
        $proxy->getConstant( 'FOO' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetConstantsDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getConstants' );
        $proxy->getConstants();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyImplementsInterfaceDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'implementsInterface' );
        $proxy->implementsInterface( '\Iterator' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetInterfaceNameDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getInterfaceNames' );
        $proxy->getInterfaceNames();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetInterfacesDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getInterfaces' );
        $proxy->getInterfaces();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetParentClassDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getParentClass' );
        $proxy->getParentClass();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetConstructorDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getConstructor' );
        $proxy->getConstructor();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyHasMethodDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'hasMethod' );
        $proxy->hasMethod( 'foo' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetMethodDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getMethod' );
        $proxy->getMethod( 'foo' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetMethodsDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getMethods' );
        $proxy->getMethods( 42 );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyHasPropertyDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'hasProperty' );
        $proxy->hasProperty( '_foo' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetPropertyDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getProperty' );
        $proxy->getProperty( '_foo' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetPropertiesDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getProperties' );
        $proxy->getProperties( 23 );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetDefaultPropertiesDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getDefaultProperties' );
        $proxy->getDefaultProperties();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetStaticPropertyValueDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getStaticPropertyValue' );
        $proxy->getStaticPropertyValue( 'foo' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxySetStaticPropertyValueDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'setStaticPropertyValue' );
        $proxy->setStaticPropertyValue( '_foo', 42 );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetStaticPropertiesDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getStaticProperties' );
        $proxy->getStaticProperties();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetStartLineDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getStartLine' );
        $proxy->getStartLine();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetEndLineDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getEndLine' );
        $proxy->getEndLine();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetExtensionDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getExtension' );
        $proxy->getExtension();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyGetExtensionNameDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'getExtensionName' );
        $proxy->getExtensionName();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyNewInstanceDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'newInstance' );
        $proxy->newInstance( null );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyNewInstanceArgsDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( 'newInstanceArgs' );
        $proxy->newInstanceArgs();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassProxy
     * @group reflection
     * @group unittest
     */
    public function testProxyToStringDelegatesToRealSubject()
    {
        $proxy = $this->_createClassProxy( '__toString' );
        $proxy->__toString();
    }

    /**
     * Create a prepared proxy instance.
     *
     * @param string $methodName Name of the tested method.
     *
     * @return \org\pdepend\reflection\ReflectionClassProxy
     */
    private function _createClassProxy( $methodName )
    {
        $subject = $this->getMock( '\ReflectionClass', array( $methodName ), array( __CLASS__ ) );
        $subject->expects( $this->once() )
            ->method( $methodName );

        $session = $this->createSession();
        $session->expects( $this->once() )
            ->method( 'getClass' )
            ->with( $this->equalTo( __CLASS__ ) )
            ->will( $this->returnValue( $subject ) );

        return new ReflectionClassProxy( $session, __CLASS__ );
    }
}