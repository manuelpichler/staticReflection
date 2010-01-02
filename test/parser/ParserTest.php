<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace pdepend\reflection\parser;

use \pdepend\reflection\api\StaticReflectionClass;
use \pdepend\reflection\api\StaticReflectionInterface;
use \pdepend\reflection\api\StaticReflectionMethod;
use \pdepend\reflection\api\StaticReflectionProperty;

require_once 'BaseTest.php';

/**
 * Test cases for the parser class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ParserTest extends \pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserReturnsInstanceOfTypeStaticClass()
    {
        $class = $this->_parseClass( 'ClassWithoutNamespace' );

        $this->assertType( StaticReflectionClass::TYPE, $class );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassParentByDefaultWithFalse()
    {
        $class       = $this->_parseClass( 'ClassWithoutNamespace' );
        $parentClass = $class->getParentClass();

        $this->assertFalse( $parentClass );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithoutInterfaceByDefaultAsEmpty()
    {
        $class      = $this->_parseClass( 'ClassWithoutNamespace' );
        $interfaces = $class->getInterfaces();

        $this->assertSame( array(), $interfaces );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithParentClass()
    {
        $class       = $this->_parseClass( 'ClassWithParentClass' );
        $parentClass = $class->getParentClass();

        $this->assertType( '\ReflectionClass', $parentClass );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesCommentInParentClassName()
    {
        $class       = $this->_parseClass( 'ClassWithCommentInParentClassName' );
        $parentClass = $class->getParentClass();

        $this->assertSame( 'c\w\n\ClassWithNamespace', $parentClass->getName() );
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
        $this->assertSame( 'c\w\n\ClassWithNamespace', $class->getName() );
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
        
        $this->assertEquals( 'org\pdepend\ClassWithNamespaceParentAliased', $parentClass->getName() );
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

        $this->assertEquals( 'org\pdepend\InterfaceWithNamespaceAliased', $interfaces[0]->getName() );
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

        $this->assertType( '\ReflectionClass', $interfaces[0] );
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

        $this->assertSame( 2, count( $interfaces ) );
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
        $this->assertType( StaticReflectionInterface::TYPE, $class );
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

        $this->assertType( '\ReflectionClass', $interfaces[0] );
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

        $this->assertSame( 2, count( $interfaces ) );
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
        $this->assertSame( 'foo\bar\baz', $class->getNamespaceName() );
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
        $this->assertSame( '', $class->getNamespaceName() );
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
        $this->assertSame( 'foo\bar\baz', $class->getNamespaceName() );
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
        $this->assertSame( 'foo\bar\baz', $class->getNamespaceName() );
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

        $this->assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
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

        $this->assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
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

        $this->assertSame( 'c\w\n\ClassWithNamespace', $parentClass->getName() );

        $this->assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
        $this->assertSame( 'InterfaceWithoutNamespace', $interfaces[1]->getName() );
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

        $this->assertSame( 1, count( $methods ) );
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

        $this->assertSame( 3, count( $methods ) );
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

        $this->assertSame( 3, count( $methods ) );
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

        $this->assertSame(
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
        $this->assertTrue( $class->isAbstract() );
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
        $this->assertTrue( $class->isFinal() );
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

        $this->assertSame(
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

        $this->assertTrue( $method->isPublic() );
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

        $this->assertTrue( $method->isProtected() );
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

        $this->assertTrue( $method->isPrivate() );
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

        $this->assertTrue( $method->isFinal() );
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

        $this->assertTrue( $method->isStatic() );
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

        $this->assertTrue( $method->isAbstract() );
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

        $this->assertFalse( $method->returnsReference() );
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

        $this->assertTrue( $method->returnsReference() );
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

        $this->assertSame( 7, $method->getStartLine() );
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

        $this->assertSame( 12, $method->getEndLine() );
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

        $this->assertSame( 16, $method->getStartLine() );
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

        $this->assertSame( 19, $method->getEndLine() );
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

        $this->assertEquals( 3, $method->getNumberOfParameters() );
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

        $this->assertFalse( $params[0]->isPassedByReference() );
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

        $this->assertTrue( $params[0]->isPassedByReference() );
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

        $this->assertTrue( $params[0]->isArray() );
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

        $this->assertType( '\ReflectionClass', $params[0]->getClass() );
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

        $this->assertType( '\ReflectionClass', $params[0]->getClass() );
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

        $this->assertType( '\ReflectionClass', $params[0]->getClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\parser\Parser
     * @covers \pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsPropertyDocComment()
    {
        $class    = $this->_parseClass( 'PropertyWithComment' );
        $property = $class->getProperty( 'foo' );

        $this->assertSame(
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

        $this->assertSame( 3, count( $properties ) );
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

        $this->assertType( StaticReflectionProperty::TYPE, $property );
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

        $this->assertType( StaticReflectionProperty::TYPE, $property );
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

        $this->assertType( StaticReflectionProperty::TYPE, $property );
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

        $this->assertSame( array( 'foo' => 42, 'bar' => null, 23 => true ), $property->getValue() );
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

        $this->assertNull( $property->getValue() );
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

        $this->assertFalse( $property->getValue() );
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

        $this->assertTrue( $property->getValue() );
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

        $this->assertEquals( 3.14, $property->getValue(), '', 0.001 );
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

        $this->assertEquals( 42, $property->getValue() );
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

        $this->assertEquals( '"Manuel Pichler\'', $property->getValue() );
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
        $this->assertTrue( $class->getProperty( 'foo' )->isPrivate() );
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
        $this->assertTrue( $class->getProperty( 'foo' )->isProtected() );
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
        $this->assertTrue( $class->getProperty( 'foo' )->isPublic() );
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
        $this->assertTrue( $class->getProperty( 'foo' )->isStatic() );
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
        $this->assertSame(
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
        $this->assertSame( 6, $class->getStartLine() );
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
        $this->assertSame( 14, $class->getEndLine() );
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
        $this->assertTrue( $class->hasConstant( 'T_FOO' ) );
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
        $this->assertEquals( 3, count( $class->getConstants() ) );
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

        $this->assertEquals( '__StaticReflectionConstantValue(self::T_BAR)', $const );
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

        $this->assertEquals( '__StaticReflectionConstantValue(parent::T_BAR)', $const );
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

        $this->assertEquals( '__StaticReflectionConstantValue(Foo::T_FOO)', $const );
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

        $this->assertEquals( '__StaticReflectionConstantValue(foo\bar\Bar::T_BAR)', $const );
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

        $this->assertEquals( '__StaticReflectionConstantValue(foo\bar\Baz::T_BAZ)', $const );
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

        $this->assertEquals( '__StaticReflectionConstantValue(T_FOOBAR)', $const );
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

        $this->assertEquals( '__StaticReflectionConstantValue(foo\bar\baz\BARFOO)', $const );
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

        $this->assertEquals( '__StaticReflectionConstantValue(foo\bar\FOOBAZ)', $const );
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

        $this->assertEquals( 
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

        $this->assertEquals( $this->getPathnameForClass( 'PropertyMagicConstantFile' ), $value );
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

        $this->assertEquals( 4, $value );
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

        $this->assertEquals( 'magic\constant\PropertyMagicConstantClass', $value );
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

        $this->assertEquals( 'magic\constant', $value );
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

        $this->assertEquals( 'fooBar', $params[0]->getDefaultValue() );
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

        $this->assertEquals( 'magic\constant\ParameterMagicConstantMethod::fooBar', $params[0]->getDefaultValue() );
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
        
        $this->assertSame( array(), $method->getStaticVariables() );
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
        
        $this->assertSame(
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

        $this->assertSame(
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

        $this->assertSame(
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
