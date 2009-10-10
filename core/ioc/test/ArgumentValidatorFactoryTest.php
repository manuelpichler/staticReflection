<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

require_once 'BaseTest.php';

/**
 * Test case for the argument validator factory.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ArgumentValidatorFactoryTest extends BaseTest
{
    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        ArgumentValidatorFactory::set( null );
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        ArgumentValidatorFactory::set( null );

        parent::tearDown();
    }

    /**
     * @return void
     * @group ioc
     * @expectedException \RuntimeException
     */
    public function testFactoryGetMethodThrowsExceptionWhenNoInstanceIsConfigured()
    {
        ArgumentValidatorFactory::get();
    }
}