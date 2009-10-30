<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\parser;

use \org\pdepend\reflection\api\StaticReflectionClass;
use \org\pdepend\reflection\api\StaticReflectionInterface;
use \org\pdepend\reflection\api\StaticReflectionMethod;
use \org\pdepend\reflection\api\StaticReflectionProperty;

require_once 'BaseTest.php';

/**
 * Test cases for the parser class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ParserTest extends \org\pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserReturnsInstanceOfTypeStaticClass()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithoutNamespace' );
        $this->assertType( StaticReflectionClass::TYPE, $parser->parse() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassParentByDefaultWithFalse()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithoutNamespace' );
        $this->assertFalse( $parser->parse()->getParentClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithoutInterfaceByDefaultAsEmpty()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithoutNamespace' );
        $this->assertSame( array(), $parser->parse()->getInterfaces() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithParentClass()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithParentClass' );
        $this->assertType( StaticReflectionInterface::TYPE, $parser->parse()->getParentClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesCommentInParentClassName()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithCommentInParentClassName' );
        $this->assertSame( 'c\w\n\ClassWithNamespace', $parser->parse()->getParentClass()->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserAcceptsClassNameWithLeadingNamespaceSeparatorChar()
    {
        $parser = new Parser( $this->createParserContext(), '\c\w\n\ClassWithNamespace' );
        $this->assertSame( 'c\w\n\ClassWithNamespace', $parser->parse()->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesNamespaceAliasSyntaxForParentClass()
    {
        $parser = new Parser( $this->createParserContext(), '\org\pdepend\ClassWithNamespaceAliasedParent' );
        $parent = $parser->parse()->getParentClass();
        
        $this->assertEquals( 'org\pdepend\ClassWithNamespaceParentAliased', $parent->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesNamespaceAliasSyntaxForImplementedInterface()
    {
        $parser     = new Parser( $this->createParserContext(), '\org\pdepend\ClassWithNamespaceAliasedInterface' );
        $interfaces = $parser->parse()->getInterfaces();

        $this->assertEquals( 'org\pdepend\InterfaceWithNamespaceAliased', $interfaces[0]->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithImplementedInterface()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithImplementedInterface' );

        $interfaces = $parser->parse()->getInterfaces();
        $this->assertType( StaticReflectionInterface::TYPE, $interfaces[0] );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithMultipleImplementedInterfaces()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithMultipleImplementedInterfaces' );
        $this->assertSame( 2, count( $parser->parse()->getInterfaces() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserReturnsInstanceOfTypeStaticInterface()
    {
        $parser = new Parser( $this->createParserContext(), 'InterfaceWithoutNamespace' );
        $this->assertType( StaticReflectionInterface::TYPE, $parser->parse() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesInterfaceWithParentInterface()
    {
        $parser = new Parser( $this->createParserContext(), 'InterfaceWithParentInterface' );

        $interfaces = $parser->parse()->getInterfaces();
        $this->assertType( StaticReflectionInterface::TYPE, $interfaces[0] );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesInterfaceWithMultipleParentInterfaces()
    {
        $parser = new Parser( $this->createParserContext(), 'InterfaceWithMultipleParentInterfaces' );

        $interfaces = $parser->parse()->getInterfaces();
        $this->assertSame( 2, count( $interfaces ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesCurlyBraceSyntaxForNamespaces()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\baz\NamespaceCurlyBraceSyntax' );
        $class  = $parser->parse();

        $this->assertSame( 'foo\bar\baz', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesCurlyBraceDefaultNamespace()
    {
        $parser = new Parser( $this->createParserContext(), '\NamespaceCurlyBraceDefault' );
        $class  = $parser->parse();

        $this->assertSame( '', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesSemicolonSyntaxForNamespaces()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\baz\NamespaceSemicolonSyntax' );
        $class  = $parser->parse();

        $this->assertSame( 'foo\bar\baz', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserIgnoresCommentsInNamespaceDeclaration()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\baz\NamespaceDeclarationWithComments' );
        $class  = $parser->parse();

        $this->assertSame( 'foo\bar\baz', $class->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassUseStatementAsExpected()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassUseStatement' );

        $interfaces = $parser->parse()->getInterfaces();
        $this->assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassUseStatementWithAliasAsExpected()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassAliasedUseStatement' );

        $interfaces = $parser->parse()->getInterfaces();
        $this->assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithMultipleUseStatements()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithMultipleUseStatement' );

        $class = $parser->parse();
        $this->assertSame( 'c\w\n\ClassWithNamespace', $class->getParentClass()->getName() );

        $interfaces = $class->getInterfaces();
        $this->assertSame( 'foo\InterfaceWithNamespace', $interfaces[0]->getName() );
        $this->assertSame( 'InterfaceWithoutNamespace', $interfaces[1]->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesRegularMethodInClassAsExpected()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithMethod' );

        $class = $parser->parse();
        $this->assertSame( 1, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassWithMultipleMethods()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithMultipleMethods' );

        $class = $parser->parse();
        $this->assertSame( 3, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesInterfaceWithMultipleMethodDeclarations()
    {
        $parser = new Parser( $this->createParserContext(), 'InterfaceWithMultipleMethods' );
        $this->assertSame( 3, count( $parser->parse()->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsClassDocComment()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithDocComment' );
        $this->assertSame(
            "/**\n" .
            " * Hello Static Reflection\n" .
            " *\n" .
            " * @author Manuel Pichler\n" .
            " */",
            $parser->parse()->getDocComment()
        );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsClassAsAbstract()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassDeclaredAbstract' );
        $this->assertTrue( $parser->parse()->isAbstract() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsClassAsFinal()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassDeclaredFinal' );
        $this->assertTrue( $parser->parse()->isFinal() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsMethodDocComment()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodWithComment' );
        $this->assertSame(
            "/**\n" .
            "     * A simple method...\n" .
            "     *\n" .
            "     * @return void\n" .
            "     */",
            $parser->parse()->getMethod( 'foo' )->getDocComment()
        );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsPublic()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodPublic' );
        $this->assertTrue( $parser->parse()->getMethod( 'foo' )->isPublic() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsProtected()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodProtected' );
        $this->assertTrue( $parser->parse()->getMethod( 'foo' )->isProtected() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsPrivate()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodPrivate' );
        $this->assertTrue( $parser->parse()->getMethod( 'foo' )->isPrivate() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsFinal()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodFinal' );
        $this->assertTrue( $parser->parse()->getMethod( 'foo' )->isFinal() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsStatic()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodStatic' );
        $this->assertTrue( $parser->parse()->getMethod( 'foo' )->isStatic() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsAbstract()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodAbstract' );
        $this->assertTrue( $parser->parse()->getMethod( 'foo' )->isAbstract() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserDoesNotFlagMethodAsReturnsReference()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodNotReturningReference' );
        $this->assertFalse( $parser->parse()->getMethod( 'foo' )->returnsReference() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsMethodAsReturnsReference()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodReturnsReference' );
        $this->assertTrue( $parser->parse()->getMethod( 'foo' )->returnsReference() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedConcreteMethodStartLine()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodLineNumbers' );
        $this->assertSame( 7, $parser->parse()->getMethod( 'foo' )->getStartLine() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedConcreteMethodEndLine()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodLineNumbers' );
        $this->assertSame( 12, $parser->parse()->getMethod( 'foo' )->getEndLine() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedAbstractMethodStartLine()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodLineNumbers' );
        $this->assertSame( 16, $parser->parse()->getMethod( '_bar' )->getStartLine() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedAbstractMethodEndLine()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodLineNumbers' );
        $this->assertSame( 19, $parser->parse()->getMethod( '_bar' )->getEndLine() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedNumberOfMethodParameters()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodWithParameters' );
        $method = $parser->parse()->getMethod( 'fooBar' );

        $this->assertEquals( 3, $method->getNumberOfParameters() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserNotFlagsParameterAsPassedByReference()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodWithParameters' );
        $params = $parser->parse()->getMethod( 'fooBar' )->getParameters();

        $this->assertFalse( $params[0]->isPassedByReference() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsParameterAsPassedByReference()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodWithReferenceParameter' );
        $params = $parser->parse()->getMethod( 'fooBar' )->getParameters();

        $this->assertTrue( $params[0]->isPassedByReference() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedParameterArrayTypeHint()
    {
        $parser = new Parser( $this->createParserContext(), 'ParameterWithArrayTypeHint' );
        $params = $parser->parse()->getMethod( 'fooBar' )->getParameters();

        $this->assertTrue( $params[0]->isArray() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedParameterClassTypeHint()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodWithClassParameter' );
        $params = $parser->parse()->getMethod( 'fooBar' )->getParameters();

        $this->assertType( StaticReflectionClass::TYPE, $params[0]->getClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassTypeHintWithAliasedDefaultNamespace()
    {
        $parser = new Parser( $this->createParserContext(), 'MethodWithAliasedDefaultNamespaceClassParameter' );
        $params = $parser->parse()->getMethod( 'fooBar' )->getParameters();

        $this->assertType( StaticReflectionClass::TYPE, $params[0]->getClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassTypeHintWithAliasedNamespace()
    {
        $parser = new Parser( $this->createParserContext(), 'org\pdepend\MethodWithAliasedNamespaceClassParameter' );
        $params = $parser->parse()->getMethod( 'fooBar' )->getParameters();

        $this->assertType( StaticReflectionClass::TYPE, $params[0]->getClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsPropertyDocComment()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyWithComment' );
        $this->assertSame(
            "/**\n" .
            "     * The answer...\n" .
            "     *\n" .
            "     * @var integer\n" .
            "     */",
            $parser->parse()->getProperty( 'foo' )->getDocComment()
        );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesCommaSeparatedProperties()
    {
        $parser     = new Parser( $this->createParserContext(), 'PropertyWithCommaSeparatedProperties' );
        $properties = $parser->parse()->getProperties();

        $this->assertSame( 3, count( $properties ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithConstantDefaultValue()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyWithConstantDefaultValue' );
        $this->assertType( StaticReflectionProperty::TYPE, $parser->parse()->getProperty( 'foo' ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithArrayDefaultValue()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyWithArrayDefaultValue' );
        $this->assertType( StaticReflectionProperty::TYPE, $parser->parse()->getProperty( 'foo' ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithNestedArrayDefaultValue()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyWithNestedArrayDefaultValue' );
        $this->assertType( StaticReflectionProperty::TYPE, $parser->parse()->getProperty( '_bar' ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithNullDefaultValue()
    {
        $parser   = new Parser( $this->createParserContext(), 'PropertyWithNullDefaultValue' );
        $property = $parser->parse()->getProperty( 'fooBar' );

        $this->assertNull( $property->getValue() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithFalseDefaultValue()
    {
        $parser   = new Parser( $this->createParserContext(), 'PropertyWithFalseDefaultValue' );
        $property = $parser->parse()->getProperty( 'fooBar' );

        $this->assertFalse( $property->getValue() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithTrueDefaultValue()
    {
        $parser   = new Parser( $this->createParserContext(), 'PropertyWithTrueDefaultValue' );
        $property = $parser->parse()->getProperty( 'fooBar' );

        $this->assertTrue( $property->getValue() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithFloatDefaultValue()
    {
        $parser   = new Parser( $this->createParserContext(), 'PropertyWithFloatDefaultValue' );
        $property = $parser->parse()->getProperty( 'fooBar' );

        $this->assertEquals( 3.14, $property->getValue(), '', 0.001 );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithIntegerDefaultValue()
    {
        $parser   = new Parser( $this->createParserContext(), 'PropertyWithIntegerDefaultValue' );
        $property = $parser->parse()->getProperty( 'fooBar' );

        $this->assertEquals( 42, $property->getValue() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesPropertyWithStringDefaultValue()
    {
        $parser   = new Parser( $this->createParserContext(), 'PropertyWithStringDefaultValue' );
        $property = $parser->parse()->getProperty( 'fooBar' );

        $this->assertEquals( '"Manuel Pichler\'', $property->getValue() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsPropertyAsPrivate()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyPrivate' );
        $this->assertTrue( $parser->parse()->getProperty( 'foo' )->isPrivate() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsPropertyAsProtected()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyProtected' );
        $this->assertTrue( $parser->parse()->getProperty( 'foo' )->isProtected() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsPropertyAsPublic()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyPublic' );
        $this->assertTrue( $parser->parse()->getProperty( 'foo' )->isPublic() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserFlagsPropertyAsStatic()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyStatic' );
        $this->assertTrue( $parser->parse()->getProperty( 'foo' )->isStatic() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsClassSourceFileName()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithoutNamespace' );
        $this->assertSame(
            $this->getPathnameForClass( 'ClassWithoutNamespace' ),
            $parser->parse()->getFileName()
        );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedClassStartLine()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassLineNumbers' );
        $this->assertSame( 6, $parser->parse()->getStartLine() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserSetsExpectedClassEndLine()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassLineNumbers' );
        $this->assertSame( 14, $parser->parse()->getEndLine() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstant()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithConstant' );
        $this->assertTrue( $parser->parse()->hasConstant( 'T_FOO' ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesListOfClassConstants()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithConstantList' );
        $this->assertEquals( 3, count( $parser->parse()->getConstants() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithSelfConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithConstantValueOfSelf' );
        $const  = $parser->parse()->getConstant( 'T_FOO' );

        $this->assertEquals( '__StaticReflectionConstantValue(self::T_BAR)', $const );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithParentConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassWithConstantValueOfParent' );
        $const  = $parser->parse()->getConstant( 'T_FOO' );

        $this->assertEquals( '__StaticReflectionConstantValue(parent::T_BAR)', $const );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithClassConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\ClassWithConstantValueOfClass' );
        $const  = $parser->parse()->getConstant( 'T_FOO' );

        $this->assertEquals( '__StaticReflectionConstantValue(Foo::T_FOO)', $const );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithRelativeClassConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\ClassWithConstantValueOfClass' );
        $const  = $parser->parse()->getConstant( 'T_BAR' );

        $this->assertEquals( '__StaticReflectionConstantValue(foo\bar\Bar::T_BAR)', $const );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithNamespaceAliasedClassConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\ClassWithConstantValueOfClass' );
        $const  = $parser->parse()->getConstant( 'T_BAZ' );

        $this->assertEquals( '__StaticReflectionConstantValue(foo\bar\Baz::T_BAZ)', $const );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithGlobalConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\ClassWithConstantValueOfClass' );
        $const  = $parser->parse()->getConstant( 'T_FOOBAR' );

        $this->assertEquals( '__StaticReflectionConstantValue(T_FOOBAR)', $const );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithNamespaceConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\ClassWithConstantValueOfClass' );
        $const  = $parser->parse()->getConstant( 'T_BARFOO' );

        $this->assertEquals( '__StaticReflectionConstantValue(foo\bar\baz\BARFOO)', $const );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesClassConstantWithAliasedNamespaceConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\ClassWithConstantValueOfClass' );
        $const  = $parser->parse()->getConstant( 'T_FOOBAZ' );

        $this->assertEquals( '__StaticReflectionConstantValue(foo\bar\FOOBAZ)', $const );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantFileAsExpected()
    {
        $context = $this->createParserContext();
        $parser  = new Parser( $context, 'PropertyMagicConstantFile' );
        $value   = $parser->parse()->getProperty( 'foo' )->getValue();

        $this->assertEquals( $context->getPathname( 'PropertyMagicConstantFile' ), $value );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantLineAsExpected()
    {
        $parser = new Parser( $this->createParserContext(), 'PropertyMagicConstantLine' );
        $value  = $parser->parse()->getProperty( 'foo' )->getValue();

        $this->assertEquals( 4, $value );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantClassAsExpected()
    {
        $parser = new Parser( $this->createParserContext(), '\magic\constant\PropertyMagicConstantClass' );
        $value  = $parser->parse()->getProperty( 'foo' )->getValue();

        $this->assertEquals( 'magic\constant\PropertyMagicConstantClass', $value );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantNamespaceAsExpected()
    {
        $parser = new Parser( $this->createParserContext(), '\magic\constant\PropertyMagicConstantNamespace' );
        $value  = $parser->parse()->getProperty( 'foo' )->getValue();

        $this->assertEquals( 'magic\constant', $value );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantFunctionAsExpected()
    {
        $parser = new Parser( $this->createParserContext(), '\magic\constant\ParameterMagicConstantFunction' );
        $params = $parser->parse()->getMethod( 'fooBar' )->getParameters();

        $this->assertEquals( 'fooBar', $params[0]->getDefaultValue() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserHandlesMagicConstantMethodAsExpected()
    {
        $parser = new Parser( $this->createParserContext(), '\magic\constant\ParameterMagicConstantMethod' );
        $params = $parser->parse()->getMethod( 'fooBar' )->getParameters();

        $this->assertEquals( 'magic\constant\ParameterMagicConstantMethod::fooBar', $params[0]->getDefaultValue() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     */
    public function testParserNotEndsInEndlessLoopWhenTypeHintOnParsedClassIsUsed()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\ParameterWithTypeHintOnDeclaringClass' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidClassDeclaration()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidClassDeclaration' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidInterfaceDeclaration()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidInterfaceDeclaration' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidImplementedInterfaceDeclaration()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidImplementedInterfaceDeclaration' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForUnclosedClassScope()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidUnclosedClassScope' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForUnexpectedTokenInClassScope()
    {
        $parser = new Parser( $this->createParserContext(), 'ClassScopeWithInvalidToken' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForInvalidConstantValue()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidClassConstantValue' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForInvalidNamespaceDeclaration()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\InvalidNamespaceDeclaration' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForUnexpectedEndOfNamespaceDeclaration()
    {
        $parser = new Parser( $this->createParserContext(), 'foo\bar\UnexpectedEndOfNamespaceDeclaration' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForInvalidUseStatement()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidUseStatement' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForInvalidMethodDeclatation()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidMethodDeclaration' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\EndOfTokenStreamException
     */
    public function testParserThrowsExceptionForUnclosedMethodScope()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidUnclosedMethodScope' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @covers \org\pdepend\reflection\exceptions\ParserException
     * @covers \org\pdepend\reflection\exceptions\UnexpectedTokenException
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \org\pdepend\reflection\exceptions\UnexpectedTokenException
     */
    public function testParserThrowsExceptionForUnclosedConstantDeclaration()
    {
        $parser = new Parser( $this->createParserContext(), 'InvalidUnclosedConstantDeclaration' );
        $parser->parse();
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\parser\Parser
     * @covers \org\pdepend\reflection\parser\ParserTokens
     * @group reflection
     * @group reflection::parser
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testParserThrowsExceptionWhenRequestClassDoesNotExist()
    {
        $parser = new Parser( $this->createParserContext(), 'NoClassDefined' );
        $parser->parse();
    }
}