<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\api;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection parameter class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionParameterTest extends \org\pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testConstructorStripsLeadingDollarFromParameterName()
    {
        $parameter = new StaticReflectionParameter( '$_bar', 0 );
        $this->assertSame( '_bar', $parameter->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testConstructorKeepsParameterNameWhenNotPrefixedWithDollar()
    {
        $parameter = new StaticReflectionParameter( '_fooBar', 0 );
        $this->assertSame( '_fooBar', $parameter->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPositionReturnsExpectedResult()
    {
        $parameter = new StaticReflectionParameter( '_fooBar', 42 );
        $this->assertSame( 42, $parameter->getPosition() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDeclaringFunctionReturnsExpectedInstance()
    {
        $method    = new \ReflectionMethod( __CLASS__, __FUNCTION__ );
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initDeclaringMethod( $method );

        $this->assertSame( $method, $parameter->getDeclaringFunction() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDeclaringClassReturnsExpectedInstance()
    {
        $method    = new \ReflectionMethod( __CLASS__, __FUNCTION__ );
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initDeclaringMethod( $method );

        $this->assertEquals( $method->getDeclaringClass(), $parameter->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitDeclaringMethodThrowsLogicExceptionWhenAlreadySet()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initDeclaringMethod( new \ReflectionMethod( __CLASS__, __FUNCTION__ ) );
        $parameter->initDeclaringMethod( new \ReflectionMethod( __CLASS__, __FUNCTION__ ) );
    }
}