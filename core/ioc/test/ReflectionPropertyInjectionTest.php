<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

require_once 'BaseTest.php';

/**
 * Test case for the property inection implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ReflectionPropertyInjectionTest extends BaseTest
{
    /**
     * @var ReflectionPropertyInjection
     */
    private $_fixture = null;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $object      = new \stdClass();
        $object->foo = null;

        $this->_fixture = new ReflectionPropertyInjection( $object, 'foo' );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException \RuntimeException
     */
    public function testInjectThrowsExceptionWhenArgumentsArrayIsEmpty()
    {
        $this->_fixture->inject( array() );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException \ErrorException
     */
    public function testInjectThrowsExceptionWhenArgumentsArrayIsGreaterThanOne()
    {
        $this->_fixture->inject( array( 23, 42 ) );
    }
}