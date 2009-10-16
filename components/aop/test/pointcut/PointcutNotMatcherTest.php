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
class PointcutNotMatcherTest extends \de\buzz2ee\aop\BaseTest
{
    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsFalseWhichNegatesPreviousResults()
    {
        $matcher = new PointcutNotMatcher( $this->createPointcutMatcher( true ) );
        $this->assertFalse( $matcher->match( $this->createJoinPoint() ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueWhichNegatesPreviousResults()
    {
        $matcher = new PointcutNotMatcher( $this->createPointcutMatcher( false ) );
        $this->assertTrue( $matcher->match( $this->createJoinPoint() ) );
    }
}