<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace pdepend\reflection\api;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection interface class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionInterfaceTest extends \pdepend\reflection\BaseTest
{
    /**
     * Some zend engine constants.
     */
    const ZEND_ACC_INTERFACE = 0x80;

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionClass', StaticReflectionInterface::TYPE );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsAbstractAlwaysReturnsTrue()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertTrue( $interface->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsFinalAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isFinal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInterfaceAlwaysReturnsTrue()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertTrue( $interface->isInterface() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsTrueWhenExtendsInterface()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initInterfaces( array( new StaticReflectionInterface( __CLASS__ . 'Parent', '' ) ) );

        $this->assertTrue( $interface->isSubclassOf( __CLASS__ . 'Parent' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsTrueWhenExtendsInterfaceAndIsCalledWithLeadingBackslash()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initInterfaces( array( new StaticReflectionInterface( __CLASS__ . 'Parent', '' ) ) );

        $this->assertTrue( $interface->isSubclassOf( '\\' . __CLASS__ . 'Parent' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsFalseWhenNotExtendsInterface()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isSubclassOf( __CLASS__ . 'Parent' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsFalseOnClaseItSelf()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isSubclassOf( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetShortNameReturnsFullNameWhenNameNotContainsNamespace()
    {
        $interface = new StaticReflectionInterface( 'foo_Bar', '' );
        $this->assertSame( 'foo_Bar', $interface->getShortName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetShortNameStripsNamespaceFromFullQualifiedClassName()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertSame( 'Bar', $interface->getShortName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNamespaceNameReturnsNamespacePartFormFullQualifiedClassName()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertSame( 'foo', $interface->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetNamespaceNameReturnsEmptyStringForClassWithoutNamespace()
    {
        $interface = new StaticReflectionInterface( 'Bar', '' );
        $this->assertSame( '', $interface->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testInNamespaceReturnsTrueForClassWithNamespace()
    {
        $interface = new StaticReflectionInterface( '\foo\barBar', '' );
        $this->assertTrue( $interface->inNamespace() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testInNamespaceReturnsFalseForClassWithNamespaceDefault()
    {
        $interface = new StaticReflectionInterface( '\barBar', '' );
        $this->assertFalse( $interface->inNamespace() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testInNamespaceReturnsFalseForClassWithoutNamespace()
    {
        $interface = new StaticReflectionInterface( 'barBar', '' );
        $this->assertFalse( $interface->inNamespace() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDocCommentReturnsFalseWhenDocCommentIsEmpty()
    {
        $interface = new StaticReflectionInterface( 'barBar', '' );
        $this->assertFalse( $interface->getDocComment() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDocCommentReturnsStringWhenDocCommentIsNotEmpty()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '/** @package foo */' );
        $this->assertEquals( '/** @package foo */', $interface->getDocComment() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testHasConstantReturnsTrueWhenConstantExists()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initConstants( array( 'T_BAR' => 'droelf' ) );

        $this->assertTrue( $interface->hasConstant( 'T_BAR' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testHasConstantReturnsTrueForExistingConstantWithValueNull()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initConstants( array( 'T_BAR' => null ) );

        $this->assertTrue( $interface->hasConstant( 'T_BAR' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testHasConstantReturnsFalseWhenConstantNotExists()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initConstants( array( 'T_BAR' => 'droelf' ) );

        $this->assertFalse( $interface->hasConstant( 'T_FOO' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantReturnsFalseWhenConstantNotExists()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initConstants( array( 'T_BAR' => null ) );

        $this->assertFalse( $interface->getConstant( 'T_BAZ' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantReturnsValueWhenConstantExists()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initConstants( array( 'T_BAR' => 42 ) );

        $this->assertEquals( 42, $interface->getConstant( 'T_BAR' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantsReturnsAnEmptyArrayByDefault()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( array(), $interface->getConstants() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantsReturnsDefinedInterfaceConstants()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '/** @package foo */' );
        $interface->initConstants( array( 'T_FOO' => 42, 'T_BAR' => 23 ) );

        $this->assertEquals( array( 'T_FOO' => 42, 'T_BAR' => 23 ), $interface->getConstants() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantsReturnsInheritConstants()
    {
        $parent = new StaticReflectionInterface( __CLASS__, '' );
        $parent->initConstants( array( 'T_FOO' => 42, 'T_BAR' => 23 ) );

        $child = new StaticReflectionInterface( __CLASS__, '' );
        $child->initConstants( array( 'T_BAZ' => 13 ) );
        $child->initInterfaces( array( $parent ) );

        $this->assertEquals( array( 'T_FOO' => 42, 'T_BAR' => 23, 'T_BAZ' => 13 ), $child->getConstants() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantsReturnsInheritConstantsButNotOverwrites()
    {
        $parent = new StaticReflectionInterface( __CLASS__, '' );
        $parent->initConstants( array( 'T_FOO' => 42, 'T_BAR' => 23 ) );

        $child = new StaticReflectionInterface( __CLASS__, '' );
        $child->initConstants( array( 'T_BAR' => 13 ) );
        $child->initInterfaces( array( $parent ) );

        $this->assertEquals( array( 'T_FOO' => 42, 'T_BAR' => 13 ), $child->getConstants() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetParentClassAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->getParentClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testImplementsInterfaceReturnsTrueForSameType()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertTrue( $interface->implementsInterface( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testImplementsInterfaceWorksCaseInsensitive()
    {
        $interface = new StaticReflectionInterface( strtoupper( __CLASS__ ), '' );
        $this->assertTrue( $interface->implementsInterface( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetModifiersReturnsZendAccInterface()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( self::ZEND_ACC_INTERFACE, $interface->getModifiers() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstructorReturnsNullForConstructMethod()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods( array( new StaticReflectionMethod( '__construct', '', 0 ) ) );

        $this->assertNull( $interface->getConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstructorReturnsNullForClassNameMethod()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods( array( new StaticReflectionMethod( $interface->getShortName(), '', 0 ) ) );

        $this->assertNull( $interface->getConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testHasPropertyAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertFalse( $interface->hasProperty( 'foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPropertiesReturnsAnEmptyArray()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( array(), $interface->getProperties() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetDefaultPropertiesReturnsAnEmptyArray()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( array(), $interface->getDefaultProperties() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticPropertiesReturnsAnEmptyArray()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( array(), $interface->getStaticProperties() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticPropertyValueWithStaticReflectionValueArgumentReturnsInputArgument()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertSame( 42, $interface->getStaticPropertyValue( 'foo', 42 ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInternalAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertFalse( $interface->isInternal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsUserDefinedAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertTrue( $interface->isUserDefined() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetExtensionAlwaysReturnsNull()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertNull( $interface->getExtension() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetExtensionNameAlwaysReturnsFalse()
    {
        $interface = new StaticReflectionInterface( '\foo\Bar', '' );
        $this->assertFalse( $interface->getExtensionName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstanceReturnsTrueForMatchingInstance()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertTrue( $interface->isInstance( $this ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstanceReturnsFalseForNotMatchingInstance()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isInstance( $interface ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstantiableReturnsFalse()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isInstantiable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsIterateableReturnsFalseByDefault()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $this->assertFalse( $interface->isIterateable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsIterateableReturnsFalseForClassThatImplementsTraversable()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initInterfaces( array( new StaticReflectionInterface( 'Traversable', '' ) ) );

        $this->assertFalse( $interface->isIterateable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForInterface()
    {
        $interface = new StaticReflectionInterface( 'Foo', '' );
        $interface->initFileName( '/bar/Foo.php' );
        $interface->initStartLine( 23 );
        $interface->initEndLine( 42 );

        $actual   = $interface->__toString();
        $expected = 'Interface [ <user> interface Foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [0] {' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [0] {'  . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [0] {' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForInterfaceWithConstants()
    {
        $interface = new StaticReflectionInterface( 'Foo', '' );
        $interface->initFileName( '/bar/Foo.php' );
        $interface->initStartLine( 23 );
        $interface->initEndLine( 42 );
        $interface->initConstants(
            array( 'T_FOO' => 42, 'T_BAR' => 3.14, 'T_BAZ' => true )
        );

        $actual   = $interface->__toString();
        $expected = 'Interface [ <user> interface Foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [3] {' . PHP_EOL .
                    '    Constant [ integer T_FOO ] { 42 }' . PHP_EOL .
                    '    Constant [ double T_BAR ] { 3.14 }' . PHP_EOL .
                    '    Constant [ boolean T_BAZ ] { true }' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [0] {'  . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [0] {' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForInterfaceWithMethods()
    {
        $modifiers = StaticReflectionMethod::IS_ABSTRACT | StaticReflectionMethod::IS_PUBLIC;

        $method1 = new StaticReflectionMethod( 'x', '', $modifiers );
        $method1->initStartLine( 27 );
        $method1->initEndLine( 27 );

        $method2 = new StaticReflectionMethod( 'y', '', $modifiers );
        $method2->initStartLine( 32 );
        $method2->initEndLine( 32 );

        $interface = new StaticReflectionInterface( 'Foo', '' );
        $interface->initFileName( '/bar/Foo.php' );
        $interface->initStartLine( 23 );
        $interface->initEndLine( 42 );
        $interface->initMethods( array( $method1, $method2 ) );

        $actual   = $interface->__toString();
        $expected = 'Interface [ <user> interface Foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [0] {' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [0] {'  . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [2] {' . PHP_EOL .
                    '    Method [ <user> abstract public method x ] {' . PHP_EOL .
                    '      @@ /bar/Foo.php 27 - 27' . PHP_EOL .
                    '    }' . PHP_EOL .
                    '    Method [ <user> abstract public method y ] {' . PHP_EOL .
                    '      @@ /bar/Foo.php 32 - 32' . PHP_EOL .
                    '    }' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForInterfaceWithParentInterfaces()
    {
        $interface = new StaticReflectionInterface( 'Foo', '' );
        $interface->initFileName( '/bar/Foo.php' );
        $interface->initStartLine( 23 );
        $interface->initEndLine( 42 );
        $interface->initInterfaces(
            array(
                new StaticReflectionInterface( 'Bar', '' ),
                new StaticReflectionInterface( 'Baz', '' )
            )
        );

        $actual   = $interface->__toString();
        $expected = 'Interface [ <user> interface Foo extends Bar, Baz ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [0] {' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [0] {'  . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [0] {' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testNewInstanceThrowsNotSupportedException()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->newInstance( null );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testNewInstanceArgsThrowsNotSupportedException()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->newInstanceArgs();
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
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
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitMethodsThrowsLogicExceptionWhenCalledMoreThanOnce()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initMethods( array() );
        $interface->initMethods( array() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionInterface
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitConstantsThrowsLogicExceptionWhenCalledMoreThanOnce()
    {
        $interface = new StaticReflectionInterface( __CLASS__, '' );
        $interface->initConstants( array() );
        $interface->initConstants( array() );
    }
}