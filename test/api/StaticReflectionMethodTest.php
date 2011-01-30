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
 * @category  StaticAnalysis
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
 * Test cases for the reflection method class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionMethodTest extends \pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDocCommentReturnsFalseWhenEmptyDocCommentWasGiven()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->getDocComment() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDocCommentReturnsDocCommentWhenDocCommentContainsText()
    {
        $method = new StaticReflectionMethod( 'foo', '/** @return void */', 0 );
        $this->assertSame( '/** @return void */', $method->getDocComment() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetFileNameReturnsFileOfDeclaringClass()
    {
        $class  = new \ReflectionClass( __CLASS__ );
        $method = new StaticReflectionMethod( 'foo', '/** @return void */', 0 );
        $method->initDeclaringClass( $class );

        $this->assertSame( $class->getFileName(), $method->getFileName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetModifiersReturnsExpectedBitfield()
    {
        $bitfield = StaticReflectionMethod::IS_FINAL | StaticReflectionMethod::IS_STATIC;
        $method   = new StaticReflectionMethod( 'foo', '', $bitfield );

        $this->assertEquals( $bitfield, $method->getModifiers() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsAbstractReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsAbstractReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_ABSTRACT );
        $this->assertTrue( $method->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsFinalReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isFinal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsFinalReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_FINAL );
        $this->assertTrue( $method->isFinal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPrivateReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isPrivate() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPrivateReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PRIVATE );
        $this->assertTrue( $method->isPrivate() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsProtectedReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isProtected() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsProtectedReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PROTECTED );
        $this->assertTrue( $method->isProtected() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPublicReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isPublic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPublicReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PUBLIC );
        $this->assertTrue( $method->isPublic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsStaticReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isStatic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsStaticReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_STATIC );
        $this->assertTrue( $method->isStatic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInternalAlwaysReturnsFalse()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isInternal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsUserDefinedAlwaysReturnsTrue()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertTrue( $method->isUserDefined() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsClosureAlwaysReturnsFalse()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isClosure() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsDeprecatedAlwaysReturnsFalse()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isDeprecated() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsConstructorReturnsFalseForNonConstructMethod()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initDeclaringClass( new \ReflectionClass( '\stdClass' ) );

        $this->assertFalse( $method->isConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsConstructorReturnsFalseForPhp4StyleMethodWhenConstructMethodExists()
    {
        $method0 = new StaticReflectionMethod( '__construct', '', 0 );
        $method1 = new StaticReflectionMethod( 'stdClass', '', 0 );

        $class = new StaticReflectionClass( '\stdClass', '', 0 );
        $class->initMethods( array( $method0, $method1 ) );

        $this->assertFalse( $method1->isConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsConstructorReturnsTrueForConstructMethod()
    {
        $method = new StaticReflectionMethod( '__construct', '', 0 );
        $method->initDeclaringClass( new \ReflectionClass( '\stdClass' ) );

        $this->assertTrue( $method->isConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsConstructorReturnsTrueForPhp4StyleMethod()
    {
        $method = new StaticReflectionMethod( 'stdclass', '', 0 );
        $method->initDeclaringClass( new \ReflectionClass( '\stdClass' ) );

        $this->assertTrue( $method->isConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsConstructorReturnsTrueForAbstractMethod()
    {
        $method = new StaticReflectionMethod( '__construct', '', StaticReflectionMethod::IS_ABSTRACT );
        $method->initDeclaringClass( new \ReflectionClass( '\stdClass' ) );
        
        $this->assertTrue( $method->isConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsConstructorReturnsFalseForInterfaceMethod()
    {
        $method = new StaticReflectionMethod( '__construct', '', 0 );
        $method->initDeclaringClass( new StaticReflectionInterface( 'stdClass', '' ) );

        $this->assertFalse( $method->isConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsDestructorReturnsFalseForNonDestructMethod()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initDeclaringClass( new \ReflectionClass( '\stdClass' ) );

        $this->assertFalse( $method->isDestructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsDestructorReturnsTrueForDestructMethod()
    {
        $method = new StaticReflectionMethod( '__DESTRUCT', '', 0 );
        $method->initDeclaringClass( new \ReflectionClass( '\stdClass' ) );

        $this->assertTrue( $method->isDestructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsDestructorReturnsTrueForAbstractDestructMethod()
    {
        $method = new StaticReflectionMethod( '__destruct', '', StaticReflectionMethod::IS_ABSTRACT );
        $method->initDeclaringClass( new \ReflectionClass( '\stdClass' ) );

        $this->assertTrue( $method->isDestructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsDestructorReturnsFalseForInterfaceDestructMethod()
    {
        $method = new StaticReflectionMethod( '__destruct', '', 0 );
        $method->initDeclaringClass( new StaticReflectionInterface( 'stdClass', '' ) );

        $this->assertFalse( $method->isDestructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testReturnsReferenceReturnsFalseByDefault()
    {
        $method = new StaticReflectionMethod( 'retRef', '', 0 );
        $this->assertFalse( $method->returnsReference() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testReturnsReferenceReturnsTrueWhenItWasFlaggedWithReturnsReference()
    {
        $method = new StaticReflectionMethod( 'retRef', '', 0 );
        $method->initReturnsReference();

        $this->assertTrue( $method->returnsReference() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testInNamespaceAlwaysReturnsFalse()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->inNamespace() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetShortNameIsIdenticalWithGetName()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertSame( $method->getName(), $method->getShortName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNamespaceNameIsAlwaysAnEmptyString()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertSame( '', $method->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDeclaringClassReturnsNullByDefault()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertNull( $method->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDeclaringClassReturnsPreviousSetClassInstance()
    {
        $class  = new \ReflectionClass( __CLASS__ );
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initDeclaringClass( $class );

        $this->assertSame( $class, $method->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStartLineReturnsExpectedValue()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initStartLine( 42 );

        $this->assertSame( 42, $method->getStartLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetEndLineReturnsExpectedValue()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initEndLine( 42 );

        $this->assertSame( 42, $method->getEndLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetParametersReturnsAnEmptyArrayWhenNotInitialized()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertSame( array(), $method->getParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetParametersReturnsInitializedParameters()
    {
        $params = array(
            new StaticReflectionParameter( 'foo', 0 ),
            new StaticReflectionParameter( 'bar', 1 )
        );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( $params );

        $this->assertSame( $params, $method->getParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNumberOfParametersReturnsZeroWhenNotInitialized()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertSame( 0, $method->getNumberOfParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNumberOfParametersReturnsExpectedValue()
    {
        $params = array(
            new StaticReflectionParameter( 'foo', 0 ),
            new StaticReflectionParameter( 'bar', 1 )
        );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( $params );

        $this->assertSame( 2, $method->getNumberOfParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNumberOfRequiredParametersReturnsZeroForMethodWithZeroParameters()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array() );

        $this->assertEquals( 0, $method->getNumberOfRequiredParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNumberOfRequiredParametersReturnsZeroForMethodWithOptionalParametersOnly()
    {
        $param0 = new StaticReflectionParameter( 'foo', 0 );
        $param0->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $param1 = new StaticReflectionParameter( 'bar', 1 );
        $param1->initStaticReflectionValue( new StaticReflectionValue( 23 ) );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array( $param0, $param1 ) );

        $this->assertEquals( 0, $method->getNumberOfRequiredParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNumberOfRequiredParametersReturnsExpectedValueForMethodWithoutOptionalParameters()
    {
        $params = array(
            new StaticReflectionParameter( 'foo', 0 ),
            new StaticReflectionParameter( 'bar', 1 )
        );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( $params );

        $this->assertEquals( 2, $method->getNumberOfRequiredParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNumberOfRequiredParametersReturnsTwoForMethodWithPartialOptionalParameters()
    {
        $param0 = new StaticReflectionParameter( 'foo', 0 );
        $param0->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $param1 = new StaticReflectionParameter( 'bar', 1 );

        $param2 = new StaticReflectionParameter( 'baz', 2 );
        $param2->initStaticReflectionValue( new StaticReflectionValue( 23 ) );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array( $param0, $param1, $param2 ) );

        $this->assertEquals( 2, $method->getNumberOfRequiredParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticVariablesReturnsEmptyArrayByDefault()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertSame( array(), $method->getStaticVariables() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticVariablesReturnsPreviousConfiguredVariables()
    {
        $staticVariables = array( 'foo' => 42, 'bar' => 23 );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initStaticVariables( $staticVariables );

        $this->assertSame( $staticVariables, $method->getStaticVariables() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testInitStaticVariablesStripsLeadingDollarCharacter()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initStaticVariables( array( '$foo' => 42, '$bar' => 23 ) );

        $this->assertSame( array( 'foo' => 42, 'bar' => 23 ), $method->getStaticVariables() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetExtensionNameAlwaysReturnsFalse()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->getExtensionName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetExtensionAlwaysReturnsNull()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertNull( $method->getExtension() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPublicMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod( 'foo', '', StaticReflectionMethod::IS_PUBLIC );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> public method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForProtectedMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod( 'foo', '', StaticReflectionMethod::IS_PROTECTED );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> protected method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPrivateMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod( 'foo', '', StaticReflectionMethod::IS_PRIVATE );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> private method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPublicStaticMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod(
            'foo',
            '',
            StaticReflectionMethod::IS_PUBLIC | StaticReflectionMethod::IS_STATIC
        );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> static public method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPublicFinalMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod(
            'foo',
            '',
            StaticReflectionMethod::IS_PUBLIC | StaticReflectionMethod::IS_FINAL
        );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> final public method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForProtectedFinalMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod(
            'foo',
            '',
            StaticReflectionMethod::IS_PROTECTED | StaticReflectionMethod::IS_FINAL
        );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> final protected method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPrivateFinalMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod(
            'foo',
            '',
            StaticReflectionMethod::IS_PRIVATE | StaticReflectionMethod::IS_FINAL
        );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> final private method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPublicStaticFinalMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod(
            'foo',
            '',
            StaticReflectionMethod::IS_PUBLIC |
            StaticReflectionMethod::IS_STATIC |
            StaticReflectionMethod::IS_FINAL
        );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> final static public method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForProtectedStaticFinalMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod(
            'foo',
            '',
            StaticReflectionMethod::IS_PROTECTED |
            StaticReflectionMethod::IS_STATIC |
            StaticReflectionMethod::IS_FINAL
        );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> final static protected method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPrivateStaticFinalMethod()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod(
            'foo',
            '',
            StaticReflectionMethod::IS_PRIVATE |
            StaticReflectionMethod::IS_STATIC |
            StaticReflectionMethod::IS_FINAL
        );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> final static private method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForMethodWithParameters()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );

        $method = new StaticReflectionMethod( 'foo', '', StaticReflectionMethod::IS_PUBLIC );
        $method->initDeclaringClass( $class );
        $method->initStartLine( 23 );
        $method->initEndLine( 42 );

        $method->initParameters(
            array(
                new StaticReflectionParameter( 'x', 0 ),
                new StaticReflectionParameter( 'y', 1 ),
            )
        );

        $actual   = $method->__toString();
        $expected = 'Method [ <user> public method foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23 - 42' . PHP_EOL . PHP_EOL .
                    '  - Parameters [2] {' . PHP_EOL .
                    '    Parameter #0 [ <required> $x ]' . PHP_EOL .
                    '    Parameter #1 [ <required> $y ]' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * testGetPrototypeReturnsMethodFromParentClass
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPrototypeReturnsMethodFromParentClass()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );

        $parentClass = new StaticReflectionClass( 'Foo', '', 0 );
        $parentClass->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $declaringClass = new StaticReflectionClass( 'Bar', '', 0 );
        $declaringClass->initMethods( array( $method ) );
        $declaringClass->initParentClass( $parentClass );

        $this->assertSame( $parentClass, $method->getPrototype()->getDeclaringClass() );
    }

    /**
     * testGetPrototypeReturnsMethodFromParentInterface
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPrototypeReturnsMethodFromParentInterface()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );

        $interface = new StaticReflectionInterface( 'IFoo', '' );
        $interface->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $parentClass = new StaticReflectionClass( 'Foo', '', 0 );
        $parentClass->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );
        $parentClass->initInterfaces( array( $interface ) );

        $declaringClass = new StaticReflectionClass( 'Bar', '', 0 );
        $declaringClass->initMethods( array( $method ) );
        $declaringClass->initParentClass( $parentClass );

        $this->assertSame( $interface, $method->getPrototype()->getDeclaringClass() );
    }

    /**
     * testGetPrototypeParentInterfacePrecendence
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPrototypeParentInterfacePrecendence()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );

        $interface = new StaticReflectionInterface( 'IFoo', '' );
        $interface->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $parentClass = new StaticReflectionClass( 'Foo', '', 0 );
        $parentClass->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $declaringClass = new StaticReflectionClass( 'Bar', '', 0 );
        $declaringClass->initMethods( array( $method ) );
        $declaringClass->initParentClass( $parentClass );
        $declaringClass->initInterfaces( array( $interface ) );

        $this->assertSame( $interface, $method->getPrototype()->getDeclaringClass() );
    }

    /**
     * testGetPrototypeParentClassHidesParentInterface
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPrototypeParentClassHidesParentInterface()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );

        $interface = new StaticReflectionInterface( 'IFoo', '' );
        $interface->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $declaringClass = new StaticReflectionClass( 'Bar', '', 0 );
        $declaringClass->initMethods( array( $method ) );
        $declaringClass->initInterfaces( array( $interface ) );

        $this->assertSame( $interface, $method->getPrototype()->getDeclaringClass() );
    }

    /**
     * testGetPrototypeThrowsExceptionOnClassWithoutParent
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetPrototypeThrowsExceptionOnClassWithoutParent()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initDeclaringClass( new \ReflectionClass( __CLASS__ ) );
        $method->getPrototype();
    }

    /**
     * testGetPrototypeThrowsExceptionOnInterfaceWithoutParent
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetPrototypeThrowsExceptionOnInterfaceWithoutParent()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initDeclaringClass( new \ReflectionClass( '\Iterator' ) );
        $method->getPrototype();
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testConstructorThrowsExceptionWhenInvalidModifierWasGiven()
    {
        $method = new StaticReflectionMethod( 'foo', '', 42 );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testInvokeThrowsNotSupportedException()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->invoke( $this, 42 );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testInvokeArgsThrowsNotSupportedException()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->invokeArgs( $this, array( 42 ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testSetAccessibleThrowsNotSupportedException()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->setAccessible( true );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitDeclaringClassThrowsLogicExceptionWhenDeclaringClassWasAlreadySet()
    {
        $class  = new \ReflectionClass( __CLASS__ );
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initDeclaringClass( $class );
        $method->initDeclaringClass( $class );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitStartLineThrowsLogicExceptionWhenAlreadySet()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initStartLine( 42 );
        $method->initStartLine( 42 );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitEndLineThrowsLogicExceptionWhenAlreadySet()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initEndLine( 23 );
        $method->initEndLine( 23 );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitParametersThrowsLogicExceptionWhenAlreadySet()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array() );
        $method->initParameters( array() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitReturnsReferenceThrowsLogicExceptionWhenAlreadySet()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initReturnsReference();
        $method->initReturnsReference();
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitStaticVariablesThrowsLogicExceptionWhenAlreadySet()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initStaticVariables( array() );
        $method->initStaticVariables( array() );
    }
}