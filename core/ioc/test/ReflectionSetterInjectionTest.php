<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

require_once 'BaseTest.php';

/**
 * Test case for the property inection implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ReflectionSetterInjectionTest extends BaseTest
{
    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        ArgumentValidatorFactory::set( new ArgumentValidatorFactory() );
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
     */
    public function testInjectAcceptsArgumentOfCorrectType()
    {
        $fixture = $this->_createFixture();
        $fixture->inject( array( $this ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException de\buzz2ee\ioc\exceptions\ArgumentNotFoundException
     */
    public function testInjectThrowsExceptionForMissingArgument()
    {
        $fixture = $this->_createFixture();
        $fixture->inject( array() );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException de\buzz2ee\ioc\exceptions\ArgumentTypeException
     */
    public function testInjectThrowsExceptionForTypeArgument()
    {
        $fixture = $this->_createFixture();
        $fixture->inject( array( new \stdClass() ) );
    }

    /**
     * @return ReflectionSetterInjection
     */
    private function _createFixture()
    {
        $trace = debug_backtrace();
        return new ReflectionSetterInjection( $this, lcfirst( substr( $trace[1]['function'], 4 ) ) );
    }

    public function injectThrowsExceptionForMissingArgument( self $object ) {}
    public function injectThrowsExceptionForTypeArgument( self $object ) {}
    public function injectAcceptsArgumentOfCorrectType( parent $object ) {}
}