<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace pdepend\reflection\api;

require_once __DIR__ . '/../BaseTest.php';

/**
 * Test cases for the reflection property class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionPropertyTest extends \pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testLeadingDollarInPropertyNameIsStripped()
    {
        $property = new StaticReflectionProperty( '$foo', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertSame( 'foo', $property->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testFirstCharacterIsNotStrippedWhenItIsNotADollar()
    {
        $property = new StaticReflectionProperty( 'bar', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertSame( 'bar', $property->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDeclaringClassReturnsNullByDefault()
    {
        $property = new StaticReflectionProperty( 'bar', '', 0 );
        $this->assertNull( $property->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDeclaringClassReturnsPreviousConfiguredInstance()
    {
        $property = new StaticReflectionProperty( 'bar', '', 0 );
        $property->initDeclaringClass( $class = new \ReflectionClass( __CLASS__ ) );

        $this->assertSame( $class, $property->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDocCommentReturnsFalseWhenCommentIsEmpty()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertFalse( $property->getDocComment() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDocCommentReturnsStringWhenCommentIsNotEmpty()
    {
        $property = new StaticReflectionProperty( 'foo', '/** @var int */', 0 );
        $this->assertSame( '/** @var int */', $property->getDocComment() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetValueReturnsNullByDefault()
    {
        $property = new StaticReflectionProperty( 'foo', '', 0 );
        $this->assertNull( $property->getValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetValueReturnsConfiguredScalarStaticReflectionValue()
    {
        $property = new StaticReflectionProperty( 'foo', '', 0 );
        $property->initValue( new StaticReflectionValue( 42 ) );

        $this->assertSame( 42, $property->getValue() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetModifiersReturnsExpectedBitfield()
    {
        $property = new StaticReflectionProperty(
            'foo',
            '',
            \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_STATIC
        );

        $this->assertSame(
            \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_STATIC,
            $property->getModifiers()
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPrivateReturnsFalseWhenModifierWasNotSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertFalse( $property->isPrivate() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPrivateReturnsTrueWhenModifierWasSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PRIVATE );
        $this->assertTrue( $property->isPrivate() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsProtectedReturnsFalseWhenModifierWasNotSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PRIVATE );
        $this->assertFalse( $property->isProtected() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsProtectedReturnsTrueWhenModifierWasSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PROTECTED );
        $this->assertTrue( $property->isProtected() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPublicReturnsFalseWhenModifierWasNotSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PRIVATE );
        $this->assertFalse( $property->isPublic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsPublicReturnsTrueWhenModifierWasSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertTrue( $property->isPublic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsStaticReturnsFalseWhenModifierWasNotSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PRIVATE );
        $this->assertFalse( $property->isStatic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsStaticReturnsTrueWhenModifierWasSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_STATIC );
        $this->assertTrue( $property->isStatic() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsDefaultAlwaysReturnsTrue()
    {
        $property = new StaticReflectionProperty( 'foo', false, StaticReflectionProperty::IS_PUBLIC );
        $this->assertTrue( $property->isDefault() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPublicProperty()
    {
        $property = new StaticReflectionProperty( 'foo', false, StaticReflectionProperty::IS_PUBLIC );

        $expected = 'Property [ <default> public $foo ]';
        $actual   = $property->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForProtectedProperty()
    {
        $property = new StaticReflectionProperty( 'foo', false, StaticReflectionProperty::IS_PROTECTED );

        $expected = 'Property [ <default> protected $foo ]';
        $actual   = $property->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPrivateProperty()
    {
        $property = new StaticReflectionProperty( 'foo', false, StaticReflectionProperty::IS_PRIVATE );

        $expected = 'Property [ <default> private $foo ]';
        $actual   = $property->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPublicStaticProperty()
    {
        $property = new StaticReflectionProperty( 
            'foo',
            false,
            StaticReflectionProperty::IS_PUBLIC | StaticReflectionProperty::IS_STATIC
        );

        $expected = 'Property [ public static $foo ]';
        $actual   = $property->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForProtectedStaticProperty()
    {
        $property = new StaticReflectionProperty(
            'foo',
            false,
            StaticReflectionProperty::IS_PROTECTED | StaticReflectionProperty::IS_STATIC
        );

        $expected = 'Property [ protected static $foo ]';
        $actual   = $property->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForPrivateStaticProperty()
    {
        $property = new StaticReflectionProperty(
            'foo',
            false,
            StaticReflectionProperty::IS_PRIVATE | StaticReflectionProperty::IS_STATIC
        );

        $expected = 'Property [ private static $foo ]';
        $actual   = $property->__toString();

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testConstructorThrowsExceptionWhenInvalidModifierIsGiven()
    {
        new StaticReflectionProperty(
            'foo',
            '',
            StaticReflectionProperty::IS_PUBLIC | \ReflectionMethod::IS_FINAL
        );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetValueThrowsExceptionNotSupportedWhenObjectIsPassedAsArgument()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $property->getValue( new \stdClass );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testSetValueThrowsNotSupportedException()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $property->setValue( new \stdClass, 42 );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testSetAccessibleThrowsNotSupportedException()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $property->setAccessible( true );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionValue
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitValueThrowsLogicExceptionWhenAlreadySet()
    {
        $property = new StaticReflectionProperty( 'foo', '', 0 );
        $property->initValue( new StaticReflectionValue( 23 ) );
        $property->initValue( new StaticReflectionValue( 42 ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitDeclaringClassThrowsLogicExceptionWhenAlreadySet()
    {
        $property = new StaticReflectionProperty( 'foo', '', 0 );
        $property->initDeclaringClass( new \ReflectionClass( __CLASS__ ) );
        $property->initDeclaringClass( new \ReflectionClass( __CLASS__ ) );
    }
}