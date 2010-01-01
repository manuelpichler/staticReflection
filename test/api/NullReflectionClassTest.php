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
 * @package   pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection\api;

require_once __DIR__ . '/../BaseTest.php';

/**
 * Tests for the null reflection class implementation.
 *
 * @category  PHP
 * @package   pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class NullReflectionClassTest extends \pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNameReturnsSimpleInputValue()
    {
        $class = new NullReflectionClass( 'FooBar' );
        $this->assertSame( 'FooBar', $class->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */    
    public function testGetNameReturnsNamespacedInputValue()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( 'foo\bar\FooBar', $class->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNameReturnsInputWithoutLeadingNamespaceSeparator()
    {
        $class = new NullReflectionClass( '\FooBar' );
        $this->assertSame( '\FooBar', $class->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetShortNameReturnsSimpleInputValue()
    {
        $class = new NullReflectionClass( 'FooBar' );
        $this->assertSame( 'FooBar', $class->getShortName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetShortNameReturnsClassNameWithoutNamespace()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( 'FooBar', $class->getShortName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNamespaceNameReturnsEmptyStringForSimpleInputValue()
    {
        $class = new NullReflectionClass( 'FooBar' );
        $this->assertSame( '', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNamespaceNameReturnsEmptyStringForDefaultNamespaceValue()
    {
        $class = new NullReflectionClass( '\FooBar' );
        $this->assertSame( '', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNamespaceNameNoneEmptyStringForNamespacesValue()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( 'foo\bar', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testInNamespaceReturnsFalseForSimpleInputValue()
    {
        $class = new NullReflectionClass( 'FooBar' );
        $this->assertFalse( $class->inNamespace() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testInNamespaceReturnsFalseForDefaultNamespaceInputValue()
    {
        $class = new NullReflectionClass( '\FooBar' );
        $this->assertFalse( $class->inNamespace() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testInNamespaceReturnsTrueForNamespaceInputValue()
    {
        $class = new NullReflectionClass( '\foo\bar\FooBar' );
        $this->assertTrue( $class->inNamespace() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDocCommentReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->getDocComment() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetFileNameReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->getFileName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetModifiersReturnsZero()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( 0, $class->getModifiers() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetParentClassReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->getParentClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetInterfaceNamesReturnsAnEmptyArray()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( array(), $class->getInterfaceNames() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetInterfacesReturnsAnEmptyArray()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( array(), $class->getInterfaces() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testImplementsInterfaceReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->implementsInterface( 'Baz' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testHasPropertyReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->hasProperty( 'foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetPropertyThrowsExpectedException()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $class->getProperty( 'foo' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPropertiesReturnsAnEmptyArray()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( array(), $class->getProperties() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticPropertyValueWithStaticReflectionValueArgumentReturnsInputArgument()
    {
        $interface = new NullReflectionClass( __CLASS__, '' );
        $this->assertSame( 42, $interface->getStaticPropertyValue( 'foo', 42 ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetStaticPropertyValueThrowsException()
    {
        $interface = new NullReflectionClass( __CLASS__, '' );
        $interface->getStaticPropertyValue( 'foo' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testSetStaticPropertyValueThrowsNotSupportedException()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $class->setStaticPropertyValue( 'foo', 'bar' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticPropertiesReturnsAnEmptyArray()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( array(), $class->getStaticProperties() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testHasMethodReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->hasMethod( 'foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetMethodThrowsExpectedException()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $class->getMethod( 'foo' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsAnEmptyArray()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( array(), $class->getMethods() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStartLineReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->getStartLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetEndLineReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->getEndLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantReturnsFalseForUndefinedConstant()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->getConstant( 'FOO' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantsReturnsAnEmptyArray()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( array(), $class->getConstants() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testHasConstantAlwaysReturnsFakse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->hasConstant( 'FOO' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstructorReturnsNull()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertNull( $class->getConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDefaultPropertiesReturnsEmptyArray()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertSame( array(), $class->getDefaultProperties() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetExtensionReturnsNull()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertNull( $class->getExtension() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetExtensionNameReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->getExtensionName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsAbstractReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsFinalReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->isFinal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstanceReturnsTrueForMatchingInstance()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $this->assertTrue( $class->isInstance( $this ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstanceReturnsFalseForNotMatchingInstance()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $this->assertFalse( $class->isInstance( $class ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstantiableReturnsFalse()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $this->assertFalse( $class->isInstantiable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInterfaceReturnsFalse()
    {
        $class = new NullReflectionClass( 'foo\bar\FooBar' );
        $this->assertFalse( $class->isInterface() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInternalReturnsFalse()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $this->assertFalse( $class->isInternal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsIterateableReturnsFalse()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $this->assertFalse( $class->isIterateable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsFalse()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $this->assertFalse( $class->isSubclassOf( 'Foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsUserDefinedReturnsFalse()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $this->assertFalse( $class->isUserDefined() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testNewInstanceThrowsNotSupportedException()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $class->newInstance( null );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testNewInstanceArgsThrowsNotSupportedException()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $class->newInstanceArgs();
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\NullReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedRepresentation()
    {
        $class = new NullReflectionClass( __CLASS__, '' );
        $this->assertEquals( 'Class [ 42 ]', $class->__toString() );
    }
}
