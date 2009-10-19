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
    public function testIsAbstractAlwaysReturnsTrue()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertTrue( $interface->isAbstract() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testIsFinalAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isFinal() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testIsInterfaceAlwaysReturnsTrue()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertTrue( $interface->isInterface() );
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
    public function testGetNamespaceNameReturnsEmptyStringForClassWithoutNamespace()
    {
        $interface = new StaticReflectionInterface( 'Bar', '' );
        $this->assertSame( '', $interface->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetInterfacesReturnsExpectedArray()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initInterfaces( 
            array(
                new StaticReflectionInterface( __CLASS__ . '1', '' ),
                new StaticReflectionInterface( __CLASS__ . '2', '' )
            )
        );

        $this->assertSame( 2, count( $interface->getInterfaces() ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetInterfacesAlsoReturnsInheritInterfaces()
    {
        $interface0 = new StaticReflectionInterface( __CLASS__ . '0', '' );
        $interface1 = new StaticReflectionInterface( __CLASS__ . '1', '' );
        $interface2 = new StaticReflectionInterface( __CLASS__ . '2', '' );
        
        $interface0->initInterfaces( array( $interface1 ) );
        $interface1->initInterfaces( array( $interface2 ) );

        $this->assertSame( 2, count( $interface0->getInterfaces() ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetInterfacesReturnsEachInterfaceOnlyOnce()
    {
        $interface0 = new StaticReflectionInterface( __CLASS__ . '0', '' );
        $interface1 = new StaticReflectionInterface( __CLASS__ . '1', '' );
        $interface2 = new StaticReflectionInterface( __CLASS__ . '2', '' );

        $interface0->initInterfaces( array( $interface1, $interface2 ) );
        $interface1->initInterfaces( array( $interface2 ) );

        $this->assertSame( 2, count( $interface0->getInterfaces() ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetInterfaceNamesReturnsExpectedArrayOfStrings()
    {
        $interface0 = new StaticReflectionInterface( __CLASS__ . '0', '' );
        $interface1 = new StaticReflectionInterface( __CLASS__ . '1', '' );
        $interface2 = new StaticReflectionInterface( __CLASS__ . '2', '' );

        $interface0->initInterfaces( array( $interface1, $interface2 ) );
        $interface1->initInterfaces( array( $interface2 ) );

        $this->assertSame(
            array( __CLASS__ . '1', __CLASS__ . '2' ),
            $interface0->getInterfaceNames()
        );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testImplementsInterfaceReturnsTrueForSameType()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertTrue( $interface->implementsInterface( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testImplementsInterfaceReturnsTrueForParentType()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initInterfaces( array( new StaticReflectionInterface( 'Parent', '' ) ) );
        
        $this->assertTrue( $interface->implementsInterface( 'Parent' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testImplementsInterfaceWorksCaseInsensitive()
    {
        $interface = new StaticReflectionInterface( strtoupper( __CLASS__ ), '' );
        $this->assertTrue( $interface->implementsInterface( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testHasMethodReturnsTrueWhenMethodForNameExists()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods( array( new StaticReflectionMethod( 'fooBar', '', 0 ) ) );

        $this->assertTrue( $interface->hasMethod( 'fooBar' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testHasMethodReturnsTrueWhenParentDeclaresMethod()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initInterfaces( array( new \ReflectionClass( '\Iterator' ) ) );

        $this->assertTrue( $interface->hasMethod( 'current' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetMethodReturnsDeclaredInstance()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods( array( new StaticReflectionMethod( 'fooBar', '', 0 ) ) );

        $this->assertNotNull( $interface->getMethod( 'fooBar' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetMethodReturnsMethodDeclaredInParent()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initInterfaces( array( new \ReflectionClass( '\Iterator' ) ) );

        $this->assertType( '\ReflectionMethod', $interface->getMethod( 'next' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testHasMethodHandlesNamesCaseInsensitive()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
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
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods( array() );

        $this->assertFalse( $interface->hasMethod( 'fooBar' ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsDefinedOnContextClass()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $this->assertSame( 1, count( $interface->getMethods() ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsDefinedOnParentClass()
    {
        $interface0 = new StaticReflectionInterface( __CLASS__, '' );
        $interface0->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $interface1 = new StaticReflectionInterface( __CLASS__, '' );
        $interface1->initMethods( array( new StaticReflectionMethod( 'bar', '', 0 ) ) );

        $interface0->initInterfaces( array( $interface1 ) );

        $this->assertSame( 2, count( $interface0->getMethods() ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsWithSameNameOnlyOnce()
    {
        $interface0 = new StaticReflectionInterface( __CLASS__, '' );
        $interface0->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $interface1 = new StaticReflectionInterface( __CLASS__, '' );
        $interface1->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $interface0->initInterfaces( array( $interface1 ) );

        $this->assertSame( 1, count( $interface0->getMethods() ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetMethodReturnsHighestMethodInInheritenceHierarchy()
    {
        $interface0 = new StaticReflectionInterface( __CLASS__, '' );
        $interface0->initMethods(
            array( $method0 = new StaticReflectionMethod( 'foo', '', 0 ) )
        );

        $interface1 = new StaticReflectionInterface( __CLASS__, '' );
        $interface1->initMethods(
            array( $method1 = new StaticReflectionMethod( 'foo', '', 0 ) )
        );

        $interface0->initInterfaces( array( $interface1 ) );

        $interfaces = $interface0->getMethods();
        $this->assertSame( $method0, $interfaces[0] );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsWorksCaseInsensitive()
    {
        $interface0 = new StaticReflectionInterface( __CLASS__, '' );
        $interface0->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $interface1 = new StaticReflectionInterface( __CLASS__, '' );
        $interface1->initMethods( array( new StaticReflectionMethod( 'FOO', '', 0 ) ) );

        $interface0->initInterfaces( array( $interface1 ) );

        $this->assertSame( 1, count( $interface0->getMethods() ) );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsFiltersBySuppliedModifierArgument()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods(
            array(
                new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_FINAL ),
                new StaticReflectionMethod( 'bar', '', \ReflectionMethod::IS_PRIVATE ),
            )
        );

        $this->assertSame( 1, count( $interface->getMethods( \ReflectionMethod::IS_FINAL ) ) );
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
    public function testGetPropertiesReturnsAnEmptyArray()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( array(), $interface->getProperties() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetDefaultPropertiesReturnsAnEmptyArray()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( array(), $interface->getDefaultProperties() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     */
    public function testGetStaticPropertiesReturnsAnEmptyArray()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( array(), $interface->getStaticProperties() );
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
     */
    public function testGetEndLineReturnsExpectedValue()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initEndLine( 42 );

        $this->assertSame( 42, $interface->getEndLine() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetMethodThrowsExceptionWhenNoMatchingMethodExists()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->getMethod( 'valid' );
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
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->setStaticPropertyValue( 'foo', 'bar' );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetStaticPropertyValueThrowsException()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->getStaticPropertyValue( 'foo' );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetPropertyThrowsNotSupportedException()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->getProperty( 'foo' );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testNewInstanceThrowsNptSupportedException()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->newInstance( null );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testNewInstanceArgsThrowsNptSupportedException()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->newInstanceArgs();
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
        $interface = new StaticReflectionInterface( __CLASS__, '' );
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
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initStartLine( 23 );
        $interface->initStartLine( 42 );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitEndLineThrowsLogicExceptionWhenLineNumberWasAlreadySet()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initEndLine( 23 );
        $interface->initEndLine( 42 );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitInterfacesThrowsLogicExceptionWhenCalledMoreThanOnce()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initInterfaces( array() );
        $interface->initInterfaces( array() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionInterface
     * @group reflection
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitMethodsThrowsLogicExceptionWhenCalledMoreThanOnce()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods( array() );
        $interface->initMethods( array() );
    }
}