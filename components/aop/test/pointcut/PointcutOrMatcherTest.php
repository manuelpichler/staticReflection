<?php
/**
 * I provide completely working code within this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\aop\pointcut;

require_once 'BaseTest.php';

/**
 * Test case for the pointcut or matcher.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutOrMatcherTest extends \de\buzz2ee\aop\BaseTest
{
    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueForLeftTrueAndRightTrue()
    {
        $matcher = new PointcutOrMatcher(
            $this->createPointcutMatcher( true ),
            $this->createPointcutMatcher( true )
        );

        $this->assertTrue( $matcher->match( $this->createJoinPoint() ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueForLeftFalseAndRightTrue()
    {
        $matcher = new PointcutOrMatcher(
            $this->createPointcutMatcher( false ),
            $this->createPointcutMatcher( true )
        );

        $this->assertTrue( $matcher->match( $this->createJoinPoint() ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsTrueForLeftTrueAndRightFalse()
    {
        $matcher = new PointcutOrMatcher(
            $this->createPointcutMatcher( true ),
            $this->createPointcutMatcher( false )
        );

        $this->assertTrue( $matcher->match( $this->createJoinPoint() ) );
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testMatchReturnsFalseForLeftFalseAndRightFalse()
    {
        $matcher = new PointcutOrMatcher(
            $this->createPointcutMatcher( false ),
            $this->createPointcutMatcher( false )
        );

        $this->assertFalse( $matcher->match( $this->createJoinPoint() ) );
    }
}