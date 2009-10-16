<?php
/**
 * I provide completely working code within this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\aop\pointcut;

require_once 'BaseTest.php';

/**
 * Test case for the pointcut execution matcher.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutExecutionMatcherTest extends \de\buzz2ee\aop\BaseTest
{
    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueForIdenticalClassAndMethodName()
    {
        $joinPoint = $this->createJoinPoint( 'Foo', 'bar', 'public' );
        $matcher   = new PointcutExecutionMatcher( 'Foo', 'bar', '*' );

        $this->assertTrue( $matcher->match( $joinPoint ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueForIdenticalNamespaceClassAndMethodName()
    {
        $joinPoint = $this->createJoinPoint( '\foo\bar\Baz', 'bar', 'public' );
        $matcher   = new PointcutExecutionMatcher( '\foo\bar\Baz', 'bar', '*' );

        $this->assertTrue( $matcher->match( $joinPoint ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueForWildcardClassNameAndIdenticalMethodName()
    {
        $joinPoint = $this->createJoinPoint( '\foo\bar\Baz', 'bar', 'public' );
        $matcher   = new PointcutExecutionMatcher( '*\bar\Baz', 'bar', '*' );

        $this->assertTrue( $matcher->match( $joinPoint ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueForIdenticalClassNameAndWildcardMethodName()
    {
        $joinPoint = $this->createJoinPoint( '\foo\bar\Baz', 'bar', 'public' );
        $matcher   = new PointcutExecutionMatcher( '\foo\bar\Baz', 'b*', '*' );

        $this->assertTrue( $matcher->match( $joinPoint ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueForWildcardClassNameAndWildcardMethodName()
    {
        $joinPoint = $this->createJoinPoint( '\foo\bar\Baz', 'bar', 'public' );
        $matcher   = new PointcutExecutionMatcher( '*\Baz', '*', '*' );

        $this->assertTrue( $matcher->match( $joinPoint ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsFalseForCaseSensitiveTypoInClassName()
    {
        $joinPoint = $this->createJoinPoint( '\foo\bar\Baz', 'bar', 'public' );
        $matcher   = new PointcutExecutionMatcher( '\foo\bar\baz', 'bar', 'public' );

        $this->assertFalse( $matcher->match( $joinPoint ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsFalseForCaseSensitiveTypoInMethodName()
    {
        $joinPoint = $this->createJoinPoint( '\foo\bar\Baz', 'bar', 'public' );
        $matcher   = new PointcutExecutionMatcher( '\foo\bar\Baz', 'Bar', 'public' );

        $this->assertFalse( $matcher->match( $joinPoint ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsFalseForPrivateMatcherAndPublicMethod()
    {
        $joinPoint = $this->createJoinPoint( '\foo\bar\Baz', 'bar', 'public' );
        $matcher   = new PointcutExecutionMatcher( '*\Baz', '*', 'private' );

        $this->assertFalse( $matcher->match( $joinPoint ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsFalseForProtectedMatcherAndPrivateMethod()
    {
        $joinPoint = $this->createJoinPoint( '\foo\bar\Baz', 'bar', 'private' );
        $matcher   = new PointcutExecutionMatcher( '\foo\bar\Baz', 'bar', 'protected' );

        $this->assertFalse( $matcher->match( $joinPoint ) );
    }
}