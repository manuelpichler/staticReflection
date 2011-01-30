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

require_once __DIR__ . '/BaseCompatibilityTest.php';

/**
 * Tests the api compatiblility between the null reflection class implementation
 * and PHP's native api.
 *
 * @category  PHP
 * @package   pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class CompatibilityNullReflectionClassTest extends BaseCompatibilityTest
{
    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testNullVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionClass', NullReflectionClass::TYPE );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetDocCommentReturnsFalse()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame( $internal->getDocComment(), $null->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetFileNameReturnsFalse()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame( $internal->getFileName(), $null->getFileName() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStartLineReturnsFalse()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame( $internal->getStartLine(), $null->getStartLine() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetEndLineReturnsFalse()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame( $internal->getEndLine(), $null->getEndLine() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetModifiers()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->getModifiers(), $null->getModifiers() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetParentClassReturnsFalse()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->getParentClass(), $null->getParentClass() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetInterfaceNamesReturnsAnEmptyArray()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->getInterfaceNames(), $null->getInterfaceNames() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetInterfacesReturnsAnEmptyArray()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->getInterfaces(), $null->getInterfaces() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testImplementsInterfaceReturnsFalse()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame(
            $internal->implementsInterface( '\Iterator' ),
            $null->implementsInterface( '\Iterator' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testHasPropertyReturnsFalse()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->hasProperty( 'foo' ), $null->hasProperty( 'foo' ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetPropertyThrowsExpectedException()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $expected = $this->executeFailingMethod( $internal, 'getProperty', 'foo' );
        $actual   = $this->executeFailingMethod( $null, 'getProperty', 'foo' );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetPropertiesReturnsAnEmptyArray()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->getProperties(), $null->getProperties() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticPropertyValueForUnknownPropertyExceptionMessage()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $null     = $this->createNullClass( 'CompatInterfaceSimple' );

        $expected = $this->executeFailingMethod( $internal, 'getStaticPropertyValue', 'foo' );
        $actual   = $this->executeFailingMethod( $null, 'getStaticPropertyValue', 'foo' );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticPropertyValueForUnknownPropertyWithStaticReflectionValue()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $null     = $this->createNullClass( 'CompatInterfaceSimple' );

        $this->assertEquals(
            $internal->getStaticPropertyValue( 'foo', 42 ),
            $null->getStaticPropertyValue( 'foo', 42 )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticPropertiesReturnsAnEmptyArray()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame(
            $internal->getStaticProperties(),
            $null->getStaticProperties()
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testHasMethodReturnsFalse()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->hasMethod( 'foo' ), $null->hasMethod( 'foo' ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetMethodThrowsExpectedException()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $expected = $this->executeFailingMethod( $internal, 'getMethod', 'foo' );
        $actual   = $this->executeFailingMethod( $null, 'getMethod', 'foo' );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetMethodsReturnsAnEmptyArray()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->getMethods(), $null->getMethods() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstantReturnsFalseForUndefinedConstant()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame(
            $internal->getConstant( 'FOO' ),
            $null->getConstant( 'FOO' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testHasConstantReturnsAlwaysFalse()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame( 
            $internal->hasConstant( 'FOO' ),
            $null->hasConstant( 'FOO' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstantsReturnsAnEmptyArray()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame( $internal->getConstants(), $null->getConstants() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstructorReturnsNull()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame( $internal->getConstructor(), $null->getConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetDefaultPropertiesReturnsEmptyArray()
    {
        $internal = $this->createInternalClass( '\Iterator' );
        $null     = $this->createNullClass( '\Iterator' );

        $this->assertSame( $internal->getDefaultProperties(), $null->getDefaultProperties() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsAbstractReturnsFalse()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->isAbstract(), $null->isAbstract() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsFinalReturnsFalse()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->isFinal(), $null->isFinal() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsInterfaceReturnsFalse()
    {
        $internal = $this->createInternalClass( '\stdClass' );
        $null     = $this->createNullClass( '\stdClass' );

        $this->assertSame( $internal->isInterface(), $null->isInterface() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsInstantiableReturnsFalse()
    {
        $internal = $this->createInternalClass( '\ArrayAccess' );
        $null     = $this->createNullClass( '\ArrayAccess' );

        $this->assertSame( $internal->isInstantiable(), $null->isInstantiable() );
    }

    /**
     * Creates a null reflection class instance.
     *
     * @param string $name The class name
     *
     * @return \pdepend\reflection\api\NullReflectionClass
     */
    protected function createNullClass( $name )
    {
        return new NullReflectionClass( $name );
    }
}