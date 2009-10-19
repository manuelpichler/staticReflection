<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection property class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionPropertyTest extends BaseTest
{
    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionProperty', StaticReflectionProperty::TYPE );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
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
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
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
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
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
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
     * @group reflection
     * @group unittest
     */
    public function testGetDocCommentReturnsStringWhenCommentIsNotEmpty()
    {
        $property = new StaticReflectionProperty( 'foo', '/** @var int */', StaticReflectionProperty::IS_PUBLIC );
        $this->assertSame( '/** @var int */', $property->getDocComment() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
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
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
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
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
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
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
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
     * @covers \de\buzz2ee\reflection\StaticReflectionProperty
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testSetAccessibleThrowsNotSupportedException()
    {
        $property = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_PUBLIC );
        $property->setAccessible( true );
    }

    /**
     * Asserts that the public api of the given classes is equal.
     *
     * @param string $classExpected
     * @param string $classActual
     *
     * @return void
     */
    protected function assertPublicApiEquals( $classExpected, $classActual )
    {
        $expected = $this->getPublicMethods( $classExpected );
        $actual   = $this->getPublicMethods( $classActual );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @param string $className
     *
     * @return array(string)
     */
    protected function getPublicMethods( $className )
    {
        $reflection = new \ReflectionClass( $className );

        $methods = array();
        foreach ( $reflection->getMethods( \ReflectionMethod::IS_PUBLIC ) as $method )
        {
            if ( !$method->isPublic() 
                || $method->isStatic()
                || $method->getDeclaringClass()->getName() !== $className
                || is_int( strpos( $method->getDocComment(), '@access private' ) )
            ) {
                continue;
            }
            $methods[] = $method->getName();
        }
        sort( $methods );
        return $methods;
    }
}