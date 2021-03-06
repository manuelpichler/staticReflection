<?php
/**
 * This file is part of the static reflection component.
 *
 * PHP Version 5
 *
 * Copyright (c) 2009-2011, Manuel Pichler <mapi@pdepend.org>.
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
 * @package   pdepend\reflection\parser
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection\parser;

use \pdepend\reflection\api\StaticReflectionClass;
use \pdepend\reflection\api\StaticReflectionInterface;
use \pdepend\reflection\api\StaticReflectionMethod;
use \pdepend\reflection\api\StaticReflectionProperty;

require_once __DIR__ . '/../BaseTest.php';

/**
 * Test cases for the parser class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 * 
 * @covers \pdepend\reflection\parser\Parser
 * @covers \pdepend\reflection\parser\ParserTokens
 * @group reflection
 * @group reflection::parser
 * @group unittest
 */
class ParserTest extends \pdepend\reflection\BaseTest
{
    /**
     * testParserReturnsInstanceOfTypeStaticClass
     * 
     * @return void
     */
    public function testParserReturnsInstanceOfTypeStaticClass()
    {
        $class = $this->_parseClass( 'ClassWithoutNamespace' );

        self::assertInstanceOf( StaticReflectionClass::TYPE, $class );
    }

    /**
     * testParserHandlesClassParentByDefaultWithFalse
     * 
     * @return void
     */
    public function testParserHandlesClassParentByDefaultWithFalse()
    {
        $class       = $this->_parseClass( 'ClassWithoutNamespace' );
        $parentClass = $class->getParentClass();

        self::assertFalse( $parentClass );
    }

    /**
     * testParserHandlesClassWithoutInterfaceByDefaultAsEmpty
     * 
     * @return void
     */
    public function testParserHandlesClassWithoutInterfaceByDefaultAsEmpty()
    {
        $class      = $this->_parseClass( 'ClassWithoutNamespace' );
        $interfaces = $class->getInterfaces();

        self::assertSame( array(), $interfaces );
    }

    /**
     * testParserHandlesClassWithParentClass
     * 
     * @return void
     */
    public function testParserHandlesClassWithParentClass()
    {
        $class       = $this->_parseClass( 'ClassWithParentClass' );
        $parentClass = $class->getParentClass();

        self::assertInstanceOf( '\ReflectionClass', $parentClass );
    }

    /**
     * testParserHandlesCommentInParentClassName
     * 
     * @return void
     */
    public function testParserHandlesCommentInParentClassName()
    {
        $class       = $this->_parseClass( 'ClassWithCommentInParentClassName' );
        $parentClass = $class->getParentClass();

        self::assertSame( 'c\w\n\ClassWithNamespace', $parentClass->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserAcceptsClassNameWithLeadingNamespaceSeparatorChar()
    {
        $class = $this->_parseClass( '\c\w\n\ClassWithNamespace' );
        self::assertSame( 'c\w\n\ClassWithNamespace', $class->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesNamespaceAliasSyntaxForParentClass()
    {
        $class       = $this->_parseClass( '\pdepend\ClassWithNamespaceAliasedParent' );
        $parentClass = $class->getParentClass();
        
        self::assertEquals( 'org\pdepend\ClassWithNamespaceParentAliased', $parentClass->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesNamespaceAliasSyntaxForImplementedInterface()
    {
        $class      = $this->_parseClass( '\pdepend\ClassWithNamespaceAliasedInterface' );
        $interfaces = $class->getInterfaces();

        self::assertEquals( 'org\pdepend\InterfaceWithNamespaceAliased', $interfaces[0]->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithImplementedInterface()
    {
        $class      = $this->_parseClass( 'ClassWithImplementedInterface' );
        $interfaces = $class->getInterfaces();

        self::assertInstanceOf( '\ReflectionClass', $interfaces[0] );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithMultipleImplementedInterfaces()
    {
        $class      = $this->_parseClass( 'ClassWithMultipleImplementedInterfaces' );
        $interfaces = $class->getInterfaces();

        self::assertSame( 2, count( $interfaces ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserReturnsInstanceOfTypeStaticInterface()
    {
        $class = $this->_parseClass( 'InterfaceWithoutNamespace' );
        self::assertInstanceOf( StaticReflectionInterface::TYPE, $class );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesInterfaceWithParentInterface()
    {
        $class      = $this->_parseClass( 'InterfaceWithParentInterface' );
        $interfaces = $class->getInterfaces();

        self::assertInstanceOf( '\ReflectionClass', $interfaces[0] );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesInterfaceWithMultipleParentInterfaces()
    {
        $class      = $this->_parseClass( 'InterfaceWithMultipleParentInterfaces' );
        $interfaces = $class->getInterfaces();

        self::assertSame( 2, count( $interfaces ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesCurlyBraceSyntaxForNamespaces()
    {
        $class = $this->_parseClass( 'foo\bar\baz\NamespaceCurlyBraceSyntax' );
        self::assertSame( 'foo\bar\baz', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesCurlyBraceDefaultNamespace()
    {
        $class = $this->_parseClass( '\NamespaceCurlyBraceDefault' );
        self::assertSame( '', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesSemicolonSyntaxForNamespaces()
    {
        $class = $this->_parseClass( 'foo\bar\baz\NamespaceSemicolonSyntax' );
        self::assertSame( 'foo\bar\baz', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserIgnoresCommentsInNamespaceDeclaration()
    {
        $class = $this->_parseClass( 'foo\bar\baz\NamespaceDeclarationWithComments' );
        self::assertSame( 'foo\bar\baz', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassUseStatementAsExpected()
    {
        $class      = $this->_parseClass( 'ClassUseStatement' );
        $interfaces = $class->getInterfaces();

        self::assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassUseStatementWithAliasAsExpected()
    {
        $class      = $this->_parseClass( 'ClassAliasedUseStatement' );
        $interfaces = $class->getInterfaces();

        self::assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithMultipleUseStatements()
    {
        $class       = $this->_parseClass( 'ClassWithMultipleUseStatement' );
        $parentClass = $class->getParentClass();
        $interfaces  = $class->getInterfaces();

        self::assertSame( 'c\w\n\ClassWithNamespace', $parentClass->getName() );

        self::assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
        self::assertSame( 'InterfaceWithoutNamespace', $interfaces[1]->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesRegularMethodInClassAsExpected()
    {
        $class   = $this->_parseClass( 'ClassWithMethod' );
        $methods = $class->getMethods();

        self::assertSame( 1, count( $methods ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithMultipleMethods()
    {
        $class   = $this->_parseClass( 'ClassWithMultipleMethods' );
        $methods = $class->getMethods();

        self::assertSame( 3, count( $methods ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesInterfaceWithMultipleMethodDeclarations()
    {
        $class   = $this->_parseClass( 'InterfaceWithMultipleMethods' );
        $methods = $class->getMethods();

        self::assertSame( 3, count( $methods ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsClassDocComment()
    {
        $class = $this->_parseClass( 'ClassWithDocComment' );

        self::assertSame(
            "/**\n" .
            " * Hello Static Reflection\n" .
            " *\n" .
            " * @author Manuel Pichler\n" .
            " */",
            $class->getDocComment()
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsClassAsAbstract()
    {
        $class = $this->_parseClass( 'ClassDeclaredAbstract' );
        self::assertTrue( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsClassAsFinal()
    {
        $class = $this->_parseClass( 'ClassDeclaredFinal' );
        self::assertTrue( $class->isFinal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsMethodDocComment()
    {
        $class  = $this->_parseClass( 'MethodWithComment' );
        $method = $class->getMethod( 'foo' );

        self::assertSame(
            "/**\n" .
            "     * A simple method...\n" .
            "     *\n" .
            "     * @return void\n" .
            "     */",
            $method->getDocComment()
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsPublic()
    {
        $class  = $this->_parseClass( 'MethodPublic' );
        $method = $class->getMethod( 'foo' );

        self::assertTrue( $method->isPublic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsProtected()
    {
        $class  = $this->_parseClass( 'MethodProtected' );
        $method = $class->getMethod( 'foo' );

        self::assertTrue( $method->isProtected() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsPrivate()
    {
        $class  = $this->_parseClass( 'MethodPrivate' );
        $method = $class->getMethod( 'foo' );

        self::assertTrue( $method->isPrivate() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsFinal()
    {
        $class  = $this->_parseClass( 'MethodFinal' );
        $method = $class->getMethod( 'foo' );

        self::assertTrue( $method->isFinal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsStatic()
    {
        $class  = $this->_parseClass( 'MethodStatic' );
        $method = $class->getMethod( 'foo' );

        self::assertTrue( $method->isStatic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsAbstract()
    {
        $class  = $this->_parseClass( 'MethodAbstract' );
        $method = $class->getMethod( 'foo' );

        self::assertTrue( $method->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserDoesNotFlagMethodAsReturnsReference()
    {
        $class  = $this->_parseClass( 'MethodNotReturningReference' );
        $method = $class->getMethod( 'foo' );

        self::assertFalse( $method->returnsReference() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsReturnsReference()
    {
        $class  = $this->_parseClass( 'MethodReturnsReference' );
        $method = $class->getMethod( 'foo' );

        self::assertTrue( $method->returnsReference() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedConcreteMethodStartLine()
    {
        $class  = $this->_parseClass( 'MethodLineNumbers' );
        $method = $class->getMethod( 'foo' );

        self::assertSame( 4, $method->getStartLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedConcreteMethodEndLine()
    {
        $class  = $this->_parseClass( 'MethodLineNumbers' );
        $method = $class->getMethod( 'foo' );

        self::assertSame( 12, $method->getEndLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedAbstractMethodStartLine()
    {
        $class  = $this->_parseClass( 'MethodLineNumbers' );
        $method = $class->getMethod( '_bar' );

        self::assertSame( 14, $method->getStartLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedAbstractMethodEndLine()
    {
        $class  = $this->_parseClass( 'MethodLineNumbers' );
        $method = $class->getMethod( '_bar' );

        self::assertSame( 19, $method->getEndLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedNumberOfMethodParameters()
    {
        $class  = $this->_parseClass( 'MethodWithParameters' );
        $method = $class->getMethod( 'fooBar' );

        self::assertEquals( 3, $method->getNumberOfParameters() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserNotFlagsParameterAsPassedByReference()
    {
        $class  = $this->_parseClass( 'MethodWithParameters' );
        $method = $class->getMethod( 'fooBar' );
        $params = $method->getParameters();

        self::assertFalse( $params[0]->isPassedByReference() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsParameterAsPassedByReference()
    {
        $class  = $this->_parseClass( 'MethodWithReferenceParameter' );
        $method = $class->getMethod( 'fooBar' );
        $params = $method->getParameters();

        self::assertTrue( $params[0]->isPassedByReference() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedParameterArrayTypeHint()
    {
        $class  = $this->_parseClass( 'ParameterWithArrayTypeHint' );
        $method = $class->getMethod( 'fooBar' );
        $params = $method->getParameters();

        self::assertTrue( $params[0]->isArray() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedParameterClassTypeHint()
    {
        $class  = $this->_parseClass( 'MethodWithClassParameter' );
        $method = $class->getMethod( 'fooBar' );
        $params = $method->getParameters();

        self::assertInstanceOf( '\ReflectionClass', $params[0]->getClass() );
    }

    /**
     * testParserHandlesConsecutiveNamespacesCorrect
     *
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesConsecutiveNamespacesCorrect()
    {
        $class = $this->_parseClass( 'ConsecutiveNamespaces' );
        self::assertEquals( 'bar\ConsecutiveNamespaces', $class->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassTypeHintWithAliasedDefaultNamespace()
    {
        $class  = $this->_parseClass( 'MethodWithAliasedDefaultNamespaceClassParameter' );
        $method = $class->getMethod( 'fooBar' );
        $params = $method->getParameters();

        self::assertInstanceOf( '\ReflectionClass', $params[0]->getClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassTypeHintWithAliasedNamespace()
    {
        $class  = $this->_parseClass( 'pdepend\MethodWithAliasedNamespaceClassParameter' );
        $method = $class->getMethod( 'fooBar' );
        $params = $method->getParameters();

        self::assertInstanceOf( '\ReflectionClass', $params[0]->getClass() );
    }
    
    /**
     * testParserHandlesSelfTypeHint
     * 
     * @return void
     */
    public function testParserHandlesSelfTypeHint()
    {
        $class  = $this->_parseClass( 'pdepend\MethodWithSelfClassParameter' );
        $method = $class->getMethod( 'fooBar' );
        $params = $method->getParameters();

        self::assertInstanceOf( '\ReflectionClass', $params[0]->getClass() );
    }
    
    /**
     * testParserHandlesParentTypeHint
     * 
     * @return void
     */
    public function testParserHandlesParentTypeHint()
    {
        $class  = $this->_parseClass( 'pdepend\MethodWithParentClassParameter' );
        $method = $class->getMethod( 'fooBar' );
        $params = $method->getParameters();

        self::assertEquals( 'MethodWithComment', $params[0]->getClass()->getName() );
    }
    
    /**
     * testParserThrowsExpectedExceptionForParentTypeHintInInvalidContext
     * 
     * @return void
     * @expectedException \pdepend\reflection\exceptions\ParserException
     */
    public function testParserThrowsExpectedExceptionForParentTypeHintInInvalidContext()
    {
        $this->_parseClass( 'pdepend\MethodWithInvalidParentClassParameter' );
    }

    /**
     * @return void
     */
    public function testParserSetsPropertyDocComment()
    {
        $class    = $this->_parseClass( 'PropertyWithComment' );
        $property = $class->getProperty( 'foo' );

        self::assertEquals(
            "/**\n" .
            "     * The answer...\n" .
            "     *\n" .
            "     * @var integer\n" .
            "     */",
            $property->getDocComment()
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesCommaSeparatedProperties()
    {
        $class      = $this->_parseClass( 'PropertyWithCommaSeparatedProperties' );
        $properties = $class->getProperties();

        self::assertEquals( 3, count( $properties ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithConstantDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithConstantDefaultValue' );
        $property = $class->getProperty( 'foo' );

        self::assertInstanceOf( StaticReflectionProperty::TYPE, $property );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithArrayDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithArrayDefaultValue' );
        $property = $class->getProperty( 'foo' );

        self::assertInstanceOf( StaticReflectionProperty::TYPE, $property );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithNestedArrayDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithNestedArrayDefaultValue' );
        $property = $class->getProperty( '_bar' );

        self::assertInstanceOf( StaticReflectionProperty::TYPE, $property );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithHashArrayDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithHashArrayDefaultValue' );
        $property = $class->getProperty( 'hash' );

        self::assertEquals( array( 'foo' => 42, 'bar' => null, 23 => true ), $property->getValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithNullDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithNullDefaultValue' );
        $property = $class->getProperty( 'fooBar' );

        self::assertNull( $property->getValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithFalseDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithFalseDefaultValue' );
        $property = $class->getProperty( 'fooBar' );

        self::assertFalse( $property->getValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithTrueDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithTrueDefaultValue' );
        $property = $class->getProperty( 'fooBar' );

        self::assertTrue( $property->getValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithFloatDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithFloatDefaultValue' );
        $property = $class->getProperty( 'fooBar' );

        self::assertEquals( 3.14, $property->getValue(), '', 0.001 );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithIntegerDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithIntegerDefaultValue' );
        $property = $class->getProperty( 'fooBar' );

        self::assertEquals( 42, $property->getValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithStringDefaultValue()
    {
        $class    = $this->_parseClass( 'PropertyWithStringDefaultValue' );
        $property = $class->getProperty( 'fooBar' );

        self::assertEquals( '"Manuel Pichler\'', $property->getValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsPropertyAsPrivate()
    {
        $class = $this->_parseClass( 'PropertyPrivate' );
        self::assertTrue( $class->getProperty( 'foo' )->isPrivate() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsPropertyAsProtected()
    {
        $class = $this->_parseClass( 'PropertyProtected' );
        self::assertTrue( $class->getProperty( 'foo' )->isProtected() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsPropertyAsPublic()
    {
        $class = $this->_parseClass( 'PropertyPublic' );
        self::assertTrue( $class->getProperty( 'foo' )->isPublic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsPropertyAsStatic()
    {
        $class = $this->_parseClass( 'PropertyStatic' );
        self::assertTrue( $class->getProperty( 'foo' )->isStatic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsClassSourceFileName()
    {
        $class = $this->_parseClass( 'ClassWithoutNamespace' );
        self::assertSame(
            $this->getPathnameForClass( 'ClassWithoutNamespace' ),
            $class->getFileName()
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedClassStartLine()
    {
        $class = $this->_parseClass( 'ClassLineNumbers' );
        self::assertSame( 6, $class->getStartLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedClassEndLine()
    {
        $class = $this->_parseClass( 'ClassLineNumbers' );
        self::assertSame( 14, $class->getEndLine() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstant()
    {
        $class = $this->_parseClass( 'ClassWithConstant' );
        self::assertTrue( $class->hasConstant( 'T_FOO' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesListOfClassConstants()
    {
        $class = $this->_parseClass( 'ClassWithConstantList' );
        self::assertEquals( 3, count( $class->getConstants() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithSelfConstantValue()
    {
        $class = $this->_parseClass( 'ClassWithConstantValueOfSelf' );
        $const = $class->getConstant( 'T_FOO' );

        self::assertEquals( 42, $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserIgnoresUnknownClassConstantInSelfConstantValue()
    {
        $class = $this->_parseClass( 'ClassWithConstantValueOfSelfUnknown' );
        $const = $class->getConstant( 'T_FOO' );

        self::assertEquals( '__StaticReflectionConstantValue(self::T_BAR)', $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithParentConstantValue()
    {
        $class = $this->_parseClass( 'ClassWithConstantValueOfParent' );
        $const = $class->getConstant( 'T_FOO' );

        self::assertEquals( '__StaticReflectionConstantValue(parent::T_BAR)', $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithClassConstantValue()
    {
        $class = $this->_parseClass( 'foo\bar\ClassWithConstantValueOfClass' );
        $const = $class->getConstant( 'T_FOO' );

        self::assertEquals( '__StaticReflectionConstantValue(Foo::T_FOO)', $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithRelativeClassConstantValue()
    {
        $class = $this->_parseClass( 'foo\bar\ClassWithConstantValueOfClass' );
        $const = $class->getConstant( 'T_BAR' );

        self::assertEquals( '__StaticReflectionConstantValue(foo\bar\Bar::T_BAR)', $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithNamespaceAliasedClassConstantValue()
    {
        $class = $this->_parseClass( 'foo\bar\ClassWithConstantValueOfClass' );
        $const = $class->getConstant( 'T_BAZ' );

        self::assertEquals( '__StaticReflectionConstantValue(foo\bar\Baz::T_BAZ)', $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithGlobalConstantValue()
    {
        $class = $this->_parseClass( 'foo\bar\ClassWithConstantValueOfClass' );
        $const = $class->getConstant( 'T_FOOBAR' );

        self::assertEquals( '__StaticReflectionConstantValue(T_FOOBAR)', $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithNamespaceConstantValue()
    {
        $class = $this->_parseClass( 'foo\bar\ClassWithConstantValueOfClass' );
        $const = $class->getConstant( 'T_BARFOO' );

        self::assertEquals( '__StaticReflectionConstantValue(foo\bar\baz\BARFOO)', $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithAliasedNamespaceConstantValue()
    {
        $class = $this->_parseClass( 'foo\bar\ClassWithConstantValueOfClass' );
        $const = $class->getConstant( 'T_FOOBAZ' );

        self::assertEquals( '__StaticReflectionConstantValue(foo\bar\FOOBAZ)', $const );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantDirAsExpected()
    {
        $class = $this->_parseClass( 'PropertyMagicConstantDir' );
        $value = $class->getProperty( 'foo' )->getValue();

        self::assertEquals( 
            dirname( $this->getPathnameForClass( 'PropertyMagicConstantDir' ) ),
            $value
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantFileAsExpected()
    {
        $class = $this->_parseClass( 'PropertyMagicConstantFile' );
        $value = $class->getProperty( 'foo' )->getValue();

        self::assertEquals( $this->getPathnameForClass( 'PropertyMagicConstantFile' ), $value );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantLineAsExpected()
    {
        $class = $this->_parseClass( 'PropertyMagicConstantLine' );
        $value = $class->getProperty( 'foo' )->getValue();

        self::assertEquals( 4, $value );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantClassAsExpected()
    {
        $class = $this->_parseClass( '\magic\constant\PropertyMagicConstantClass' );
        $value = $class->getProperty( 'foo' )->getValue();

        self::assertEquals( 'magic\constant\PropertyMagicConstantClass', $value );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantNamespaceAsExpected()
    {
        $class = $this->_parseClass( '\magic\constant\PropertyMagicConstantNamespace' );
        $value = $class->getProperty( 'foo' )->getValue();

        self::assertEquals( 'magic\constant', $value );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantFunctionAsExpected()
    {
        $class  = $this->_parseClass( '\magic\constant\ParameterMagicConstantFunction' );
        $params = $class->getMethod( 'fooBar' )->getParameters();

        self::assertEquals( 'fooBar', $params[0]->getDefaultValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantMethodAsExpected()
    {
        $class  = $this->_parseClass( '\magic\constant\ParameterMagicConstantMethod' );
        $params = $class->getMethod( 'fooBar' )->getParameters();

        self::assertEquals( 'magic\constant\ParameterMagicConstantMethod::fooBar', $params[0]->getDefaultValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserNotEndsInEndlessLoopWhenTypeHintOnParsedClassIsUsed()
    {
        $this->_parseClass( 'foo\bar\ParameterWithTypeHintOnDeclaringClass' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsEmptyArrayWhenNotStaticVariablesExistsInMethod()
    {
        $class  = $this->_parseClass( 'MethodWithoutStaticVariables' );
        $method = $class->getMethod( 'fooBar' );
        
        self::assertSame( array(), $method->getStaticVariables() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedArrayWhenStaticVariablesExistsInMethod()
    {
        $class  = $this->_parseClass( 'MethodWithStaticVariables' );
        $method = $class->getMethod( 'fooBar' );
        
        self::assertSame(
            array( 'x' => null, 'y' => null ),
            $method->getStaticVariables()
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedStaticVariablesWithStaticReflectionValue()
    {
        $class  = $this->_parseClass( 'MethodWithStaticVariablesWithDefaultValues' );
        $method = $class->getMethod( 'fooBar' );

        self::assertSame(
            array( 'foo' => 42, 'bar' => null, 'baz' => false ),
            $method->getStaticVariables()
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedStaticVariablesFromCommaSeparatedList()
    {
        $class  = $this->_parseClass( 'MethodWithStaticVariablesFromCommaSeparatedList' );
        $method = $class->getMethod( 'fooBar' );

        self::assertSame(
            array( 'foo' => 42, 'bar' => null, 'baz' => false ),
            $method->getStaticVariables()
        );
    }
    
    /**
     * testParserRegistersClassInParserContext
     * 
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserRegistersClassInParserContext()
    {
        $context = $this->createContext();
        $context->expects( $this->once() )
            ->method( 'addClass' );

        $parser = new Parser( $context );
        $parser->parseFile( $this->getPathnameForClass( 'ClassWithoutNamespace' ) );
    }
    
    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidClassDeclaration()
    {
        $this->_parseClass( 'InvalidClassDeclaration' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidConstantDeclaration()
    {
        $this->_parseClass( 'InvalidClassConstantDeclaration' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidInterfaceDeclaration()
    {
        $this->_parseClass( 'InvalidInterfaceDeclaration' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidImplementedInterfaceDeclaration()
    {
        $this->_parseClass( 'InvalidImplementedInterfaceDeclaration' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForUnclosedClassScope()
    {
        $this->_parseClass( 'InvalidUnclosedClassScope' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForUnexpectedTokenInClassScope()
    {
        $this->_parseClass( 'ClassScopeWithInvalidToken' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForInvalidConstantValue()
    {
        $this->_parseClass( 'InvalidClassConstantValue' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForInvalidNamespaceDeclaration()
    {
        $this->_parseClass( 'foo\bar\InvalidNamespaceDeclaration' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForUnexpectedEndOfNamespaceDeclaration()
    {
        $this->_parseClass( 'foo\bar\UnexpectedEndOfNamespaceDeclaration' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidUseStatement()
    {
        $this->_parseClass( 'InvalidUseStatement' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForInvalidMethodDeclatation()
    {
        $this->_parseClass( 'InvalidMethodDeclaration' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForUnclosedMethodScope()
    {
        $this->_parseClass( 'InvalidUnclosedMethodScope' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @covers \pdepend\reflection\exceptions\ParserException
     * @covers \pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForUnclosedConstantDeclaration()
    {
        $this->_parseClass( 'InvalidUnclosedConstantDeclaration' );
    }

    /**
     * This method creates a parser instance with a default context and tries to
     * parse the class for the given class name.
     *
     * @param string $className Qualified name of the searched class.
     *
     * @return \pdepend\reflection\api\StaticReflectionInterface
     */
    private function _parseClass( $className )
    {
        $parser  = new Parser( $this->createContext() );
        $classes = $parser->parseFile( $this->getPathnameForClass( $className ) );
        
        return $classes[0];
    }
}
