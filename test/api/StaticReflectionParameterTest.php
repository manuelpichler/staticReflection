<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace pdepend\reflection\api;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection parameter class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionParameterTest extends \pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
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
     * @covers \pdepend\reflection\api\StaticReflectionParameter
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
     * @covers \pdepend\reflection\api\StaticReflectionParameter
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
     * @covers \pdepend\reflection\api\StaticReflectionParameter
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
     * @covers \pdepend\reflection\api\StaticReflectionParameter
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
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testgetDefaultValueReturnsConfiguredValue()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $this->assertEquals( 42, $parameter->getDefaultValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPassedByReferenceReturnsFalseByDefault()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $this->assertFalse( $parameter->isPassedByReference() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPassedByReferenceReturnsTrueWhenInitialized()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initPassedByReference();

        $this->assertTrue( $parameter->isPassedByReference() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsOptionalReturnsFalseWhenNoStaticReflectionValueIsAvailable()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array( $parameter ) );

        $this->assertFalse( $parameter->isOptional() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsOptionalReturnsTrueWhenStaticReflectionValueIsAvailable()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array( $parameter ) );

        $this->assertTrue( $parameter->isOptional() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsOptionalReturnsTrueWhenNullStaticReflectionValueIsAvailable()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( null ) );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array( $parameter ) );

        $this->assertTrue( $parameter->isOptional() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsOptionalReturnsFalseWhenStaticReflectionValueIsAvailableButNoOptionalParamFollows()
    {
        $param0 = new StaticReflectionParameter( '_foo', 0 );
        $param0->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $param1 = new StaticReflectionParameter( '_bar', 0 );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array( $param0, $param1 ) );

        $this->assertFalse( $param0->isOptional() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsOptionalReturnsTrueWhenStaticReflectionValueIsAvailableAndOptionalParamFollows()
    {
        $param0 = new StaticReflectionParameter( '_foo', 0 );
        $param0->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $param1 = new StaticReflectionParameter( '_bar', 0 );
        $param1->initStaticReflectionValue( new StaticReflectionValue( 23 ) );

        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initParameters( array( $param0, $param1 ) );

        $this->assertTrue( $param0->isOptional() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testAllowsNullReturnsTrueWhenNoTypeHintWasSet()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $this->assertTrue( $parameter->allowsNull() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testAllowsNullReturnsFalseWhenArrayTypeHintWasSet()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initTypeHint( true );

        $this->assertFalse( $parameter->allowsNull() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testAllowsNullReturnsFalseWhenArrayTypeHintAndStaticReflectionValueWereSet()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initTypeHint( true );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $this->assertFalse( $parameter->allowsNull() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testAllowsNullReturnsTrueWhenArrayTypeHintAndNullStaticReflectionValueWereSet()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initTypeHint( true );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( null ) );

        $this->assertTrue( $parameter->allowsNull() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testAllowsNullReturnsFalseWhenClassTypeHintWasSet()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initTypeHint( new \ReflectionClass( __CLASS__ ) );

        $this->assertFalse( $parameter->allowsNull() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testAllowsNullReturnsFalseWhenClassTypeHintAndStaticReflectionValueWereSet()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initTypeHint( new \ReflectionClass( __CLASS__ ) );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $this->assertFalse( $parameter->allowsNull() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testAllowsNullReturnsTrueWhenClassTypeHintAndNullStaticReflectionValueWereSet()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initTypeHint( new \ReflectionClass( __CLASS__ ) );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( null ) );

        $this->assertTrue( $parameter->allowsNull() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testisDefaultValueAvailableReturnsFalseByDefault()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $this->assertFalse( $parameter->isDefaultValueAvailable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testisDefaultValueAvailableReturnsTrueWhenStaticReflectionValueExists()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $this->assertTrue( $parameter->isDefaultValueAvailable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsArrayReturnsFalseByDefault()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $this->assertFalse( $parameter->isArray() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsArrayReturnsTrueWhenConfigured()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initTypeHint( true );

        $this->assertTrue( $parameter->isArray() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetClassReturnsNullByDefault()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $this->assertNull( $parameter->getClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetClassReturnsPreviousConfiguredClassInstance()
    {
        $parameter = new StaticReflectionParameter( '_foo', 0 );
        $parameter->initTypeHint( $class = new \ReflectionClass( 'Iterator' ) );

        $this->assertSame( $class, $parameter->getClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForMandatoryParameter()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        
        $this->assertEquals( 'Parameter #0 [ <required> $foo ]', $parameter->__toString() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForParameterAtThirdPosition()
    {
        $parameter = new StaticReflectionParameter( 'foo', 2 );

        $this->assertEquals( 'Parameter #2 [ <required> $foo ]', $parameter->__toString() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForOptionalParameter()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initDeclaringMethod( new \ReflectionMethod( __CLASS__, __FUNCTION__ ) );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( 42 ) );

        $this->assertEquals( 'Parameter #0 [ <optional> $foo = 42 ]', $parameter->__toString() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForMandatoryParameterWithArrayTypeHint()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initTypeHint( true );

        $expected = 'Parameter #0 [ <required> array $foo ]';
        $actual   = $parameter->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForMandatoryParameterWithClassTypeHint()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initTypeHint( new \ReflectionClass( __CLASS__ ) );

        $expected = sprintf( 'Parameter #0 [ <required> %s $foo ]', __CLASS__ );
        $actual   = $parameter->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForOptionalNullParameterWithArrayTypeHint()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initDeclaringMethod( new \ReflectionMethod( __CLASS__, __FUNCTION__ ) );
        $parameter->initTypeHint( true );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( null ) );

        $expected = 'Parameter #0 [ <optional> array or NULL $foo = NULL ]';
        $actual   = $parameter->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForOptionalNullParameterWithClassTypeHint()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initDeclaringMethod( new \ReflectionMethod( __CLASS__, __FUNCTION__ ) );
        $parameter->initTypeHint( new \ReflectionClass( __CLASS__ ) );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( null ) );

        $expected = sprintf( 'Parameter #0 [ <optional> %s or NULL $foo = NULL ]', __CLASS__ );
        $actual   = $parameter->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForOptionalArrayParameterWithTypeHint()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initDeclaringMethod( new \ReflectionMethod( __CLASS__, __FUNCTION__ ) );
        $parameter->initTypeHint( true );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( array( __CLASS__ ) ) );

        $expected = 'Parameter #0 [ <optional> array $foo = Array ]';
        $actual   = $parameter->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testgetDefaultValueThrowsExceptionWhenNoStaticReflectionValueExists()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->getDefaultValue();
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitDeclaringMethodThrowsLogicExceptionWhenAlreadySet()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initDeclaringMethod( new \ReflectionMethod( __CLASS__, __FUNCTION__ ) );
        $parameter->initDeclaringMethod( new \ReflectionMethod( __CLASS__, __FUNCTION__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitPassedByReferenceThrowsLogicExceptionWhenAlreadySet()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initPassedByReference();
        $parameter->initPassedByReference();
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitTypeHintThrowsLogicExceptionWhenNotTrueOrReflectionClass()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initTypeHint( false );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitTypeHintThrowsLogicExceptionWhenAlreadySet()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initTypeHint( true );
        $parameter->initTypeHint( true );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitStaticReflectionValueThrowsLogicExceptionWhenAlreadySet()
    {
        $parameter = new StaticReflectionParameter( 'foo', 0 );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( 23 ) );
        $parameter->initStaticReflectionValue( new StaticReflectionValue( 23 ) );
    }
}