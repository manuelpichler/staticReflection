<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\api;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection property class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionPropertyTest extends \org\pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionProperty', StaticReflectionProperty::TYPE );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testLeadingDollarInPropertyNameIsStripped()
    {
        $property = new StaticReflectionProperty( '$foo', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertSame( 'foo', $property->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testFirstCharacterIsNotStrippedWhenItIsNotADollar()
    {
        $property = new StaticReflectionProperty( 'bar', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertSame( 'bar', $property->getName() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testGetDocCommentReturnsFalseWhenCommentIsEmpty()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertFalse( $property->getDocComment() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testGetDocCommentReturnsStringWhenCommentIsNotEmpty()
    {
        $property = new StaticReflectionProperty( 'foo', '/** @var int */', 0 );
        $this->assertSame( '/** @var int */', $property->getDocComment() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsPrivateReturnsFalseWhenModifierWasNotSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertFalse( $property->isPrivate() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsPrivateReturnsTrueWhenModifierWasSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PRIVATE );
        $this->assertTrue( $property->isPrivate() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsProtectedReturnsFalseWhenModifierWasNotSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PRIVATE );
        $this->assertFalse( $property->isProtected() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsProtectedReturnsTrueWhenModifierWasSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PROTECTED );
        $this->assertTrue( $property->isProtected() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsPublicReturnsFalseWhenModifierWasNotSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PRIVATE );
        $this->assertFalse( $property->isPublic() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsPublicReturnsTrueWhenModifierWasSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $this->assertTrue( $property->isPublic() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsStaticReturnsFalseWhenModifierWasNotSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PRIVATE );
        $this->assertFalse( $property->isStatic() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsStaticReturnsTrueWhenModifierWasSupplied()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_STATIC );
        $this->assertTrue( $property->isStatic() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testIsDefaultAlwaysReturnsTrue()
    {
        $property = new StaticReflectionProperty( 'foo', false, StaticReflectionProperty::IS_PUBLIC );
        $this->assertTrue( $property->isDefault() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
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
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
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
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
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
     * @covers \org\pdepend\reflection\api\StaticReflectionProperty
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testSetAccessibleThrowsNotSupportedException()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $property->setAccessible( true );
    }
}