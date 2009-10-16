<?php
/**
 * I provide completely working code within this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\aop\pointcut;

require_once 'BaseTest.php';

/**
 * Test case for the pointcut matcher factory.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutMatcherFactoryTest extends \de\buzz2ee\aop\BaseTest
{
    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        PointcutMatcherFactory::set( null );
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        PointcutMatcherFactory::set( null );
        
        parent::tearDown();
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     * @expectedException \RuntimeException
     */
    public function testStaticGetThrowsExceptionWhenItIsNotInitialized()
    {
        PointcutMatcherFactory::get();
    }

    /**
     * @return void
     * @group aop
     * @group aop::pointcut
     * @group unittest
     */
    public function testStaticGetReturnsPreviousSetFactoryInstance()
    {
        $factory = $this->getMock( PointcutMatcherFactory::TYPE );
        PointcutMatcherFactory::set( $factory );

        $this->assertSame( $factory, PointcutMatcherFactory::get() );
    }
}