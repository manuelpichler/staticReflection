<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\aop;

use com\example\aop\interfaces\PointcutMatcher;
use com\example\aop\pointcut\PointcutNotMatcher;
use com\example\aop\pointcut\PointcutNamedMatcher;
use com\example\aop\pointcut\PointcutExecutionMatcher;

require_once 'BaseTest.php';

/**
 * Test case for the pointcut expression parser.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutExpressionParserTest extends BaseTest
{
    /**
     * @return void
     * @group aop
     * @expectedException com\example\aop\exceptions\InvalidPointcutExpressionException
     */
    public function testParseMethodThrowsExceptionForEmptyExpression()
    {
        $parser = new PointcutExpressionParser();
        $parser->parse( '' );
    }

    /**
     * @return void
     * @group aop
     * @expectedException com\example\aop\exceptions\InvalidPointcutExpressionException
     */
    public function testParseMethodThrowsExceptionForSingleNotModifier()
    {
        $parser = new PointcutExpressionParser();
        $parser->parse( '!' );
    }

    /**
     * @return void
     * @group aop
     */
    public function testParseMethodReturnsInstanceOfTypePointcutMatcher()
    {
        $parser = new PointcutExpressionParser();
        $this->assertType(
            '\com\example\aop\interfaces\PointcutMatcher',
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