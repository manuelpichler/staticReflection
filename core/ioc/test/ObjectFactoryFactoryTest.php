<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

require_once 'BaseTest.php';

/**
 * Test case for the default injection factory implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ObjectFactoryFactoryTest extends BaseTest
{
    /**
     * @return void
     */
    protected function tearDown()
    {
        ObjectFactoryFactory::set( null );

        parent::tearDown();
    }

    /**
     * @return void
     * @group ioc
     * @expectedException \RuntimeException
     */
    public function testStaticGetThrowsExceptionWhenNoFactoryWasConfigured()
    {
        ObjectFactoryFactory::get();
    }
}