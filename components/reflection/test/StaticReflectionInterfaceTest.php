<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection interface class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionInterfaceTest extends BaseTest
{
    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionClass', StaticReflectionInterface::TYPE );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetFileNameReturnsExpectedResult()
    {
        $interface = new StaticReflectionInterface( 'foo_Bar', '' );
        $interface->initFileName( __FILE__ );

        $this->assertSame( __FILE__, $interface->getFileName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetShortNameReturnsFullNameWhenNameNotContainsNamespace()
    {
        $interface = new StaticReflectionInterface( 'foo_Bar', '' );
        $this->assertSame( 'foo_Bar', $interface->getShortName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetShortNameStripsNamespaceFromFullQualifiedClassName()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertSame( 'Bar', $interface->getShortName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetNamespaceNameReturnsNamespacePartFormFullQualifiedClassName()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertSame( 'foo', $interface->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testHasMethodReturnsTrueWhenMethodForNameExists()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $interface->initMethods( array( new StaticReflectionMethod( 'fooBar', '', 0 ) ) );

        $this->assertTrue( $interface->hasMethod( 'fooBar' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testHasMethodHandlesNamesCaseInsensitive()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $interface->initMethods( array( new StaticReflectionMethod( 'fooBar', '', 0 ) ) );

        $this->assertTrue( $interface->hasMethod( 'Foobar' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testHasMethodReturnsFalseWhenMethodForNameNotExists()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $interface->initMethods( array() );

        $this->assertFalse( $interface->hasMethod( 'fooBar' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testHasPropertyAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertFalse( $interface->hasProperty( 'foo' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testIsInternalAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertFalse( $interface->isInternal() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testIsUserDefinedAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertTrue( $interface->isUserDefined() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetExtensionAlwaysReturnsNull()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertNull( $interface->getExtension() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetExtensionNameAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertFalse( $interface->getExtensionName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testIsInstanceReturnsTrueForMatchingInstance()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertTrue( $interface->isInstance( $this ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testIsInstanceReturnsFalseForNotMatchingInstance()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isInstance( $interface ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testIsInstantiableReturnsFalse()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isInstantiable() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetStartLineReturnsExpectedValue()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initStartLine( 42 );

        $this->assertSame( 42, $interface->getStartLine() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testSetStaticPropertyValueThrowsNotSupportedException()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $interface->setStaticPropertyValue( 'foo', 'bar' );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitFileNameThrowsLogicExceptionWhenFileNameWasAlreadySet()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $interface->initFileName( __FILE__ );
        $interface->initFileName( __FILE__ );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitStartLineThrowsLogicExceptionWhenLineNumberWasAlreadySet()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $interface->initStartLine( 23 );
        $interface->initStartLine( 42 );
    }
}