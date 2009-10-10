<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\aop\pointcut;

require_once 'BaseTest.php';

/**
 * Test case for the pointcut expression parser.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutExpressionParserTest extends \de\buzz2ee\aop\BaseTest
{
    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        PointcutMatcherFactory::set( new PointcutMatcherFactory() );
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        PointcutMatcherFactory::set( null );

        parent::setUp();
    }

    /**
     * @return void
     * @group aop
     * @expectedException de\buzz2ee\aop\exceptions\InvalidPointcutExpressionException
     */
    public function testParseMethodThrowsExceptionForEmptyExpression()
    {
        $parser = new PointcutExpressionParser();
        $parser->parse( '' );
    }

    /**
     * @return void
     * @group aop
     * @expectedException de\buzz2ee\aop\exceptions\InvalidPointcutExpressionException
     */
    public function testParseMethodThrowsExceptionForSingleNotModifier()
    {
        $parser = new PointcutExpressionParser();
        $parser->parse( '!' );
    }

    /**
     * @return void
     * @group aop
     * @expectedException de\buzz2ee\aop\exceptions\InvalidPointcutExpressionException
     */
    public function testParseThrowsExceptionForInvalidExecutionDesignatorExpression()
    {
        $parser = new PointcutExpressionParser();
        $parser->parse( 'execution(Foo::bar)' );
    }

    /**
     * @return void
     * @group aop
     */
    public function testParseMethodReturnsInstanceOfTypePointcutMatcher()
    {
        $parser = new PointcutExpressionParser();
        $this->assertType(
            '\de\buzz2ee\aop\interfaces\PointcutMatcher',
            $parser->parse( 'execution(* *::foo()) || !execution(* Foo::bar())' )
        );
    }

    /**
     * @return void
     * @group aop
     */
    public function testParseReturnsInstanceOfTypeNotPointcutMatcher()
    {
        $parser = new PointcutExpressionParser();
        $this->assertType(
            PointcutNotMatcher::TYPE,
            $parser->parse( '!execution(Foo::bar())' )
        );
    }

    /**
     * @return void
     * @group aop
     */
    public function testParseReturnsInstanceOfTypePointcutAndMatcher()
    {
        $parser = new PointcutExpressionParser();
        $this->assertType(
            PointcutAndMatcher::TYPE,
            $parser->parse( 'Foo::bar() && Bar::baz()' )
        );
    }

    /**
     * @return void
     * @group aop
     */
    public function testParseReturnsInstanceOfTypePointcutOrMatcher()
    {
        $parser = new PointcutExpressionParser();
        $this->assertType(
            PointcutOrMatcher::TYPE,
            $parser->parse( 'Foo::bar() || Bar::baz()' )
        );
    }

    /**
     * @return void
     * @group aop
     */
    public function testParseMethodReturnsInstanceOfPointcutExecutionMatcher()
    {
        $parser = new PointcutExpressionParser();
        $this->assertType(
            PointcutExecutionMatcher::TYPE,
            $parser->parse( 'execution(*::foo())' )
        );
    }

    /**
     * @return void
     * @group aop
     */
    public function testParseMethodReturnsInstanceOfTypePointcutNamedMatcher()
    {
        $parser = new PointcutExpressionParser();
        $this->assertType(
            PointcutNamedMatcher::TYPE,
            $parser->parse( 'Foo::bar()' )
        );
    }
}