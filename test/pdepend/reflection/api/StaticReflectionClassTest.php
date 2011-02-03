<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace pdepend\reflection\api;

require_once __DIR__ . '/../BaseTest.php';

/**
 * Test cases for the reflection class class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionClassTest extends \pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetParentClassReturnsFalseWhenNoParentExists()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertFalse( $class->getParentClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetParentClassReturnsExpectedInstanceWhenParentExists()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $child  = new StaticReflectionClass( __CLASS__, '', 0 );
        $child->initParentClass( $parent );

        self::assertSame( $parent, $child->getParentClass() );
    }

    /**
     * testGetInterfacesReturnsDirectlyImplementedInterface
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetInterfacesReturnsDirectlyImplementedInterface()
    {
        $interface = new StaticReflectionInterface( 'IFoo', '' );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initInterfaces( array( $interface ) );

        self::assertSame( array( $interface ), $class->getInterfaces() );
    }

    /**
     * testGetInterfacesReturnsIndirectlyImplementedInterface
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetInterfacesReturnsIndirectlyImplementedInterface()
    {
        $interface = new StaticReflectionInterface( 'IFoo', '' );

        $parentClass = new StaticReflectionInterface( 'ParentClass', '' );
        $parentClass->initInterfaces( array( $interface ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parentClass );

        self::assertSame( array( $interface ), $class->getInterfaces() );
    }

    /**
     * testGetInterfaceNamesReturnsDirectlyImplementedInterface
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetInterfaceNamesReturnsDirectlyImplementedInterface()
    {
        $interface = new StaticReflectionInterface( 'IFoo', '' );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initInterfaces( array( $interface ) );

        self::assertSame( array( 'IFoo' ), $class->getInterfaceNames() );
    }

    /**
     * testGetInterfaceNamesReturnsIndirectlyImplementedInterface
     *
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetInterfaceNamesReturnsIndirectlyImplementedInterface()
    {
        $interface = new StaticReflectionInterface( 'IFoo', '' );

        $parentClass = new StaticReflectionInterface( 'ParentClass', '' );
        $parentClass->initInterfaces( array( $interface ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parentClass );

        self::assertSame( array( 'IFoo' ), $class->getInterfaceNames() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantsReturnsAnEmptyArrayByDefault()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertSame( array(), $class->getConstants() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantsReturnsAnArrayWithDefinedClassConstants()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initConstants( array( 'T_FOO' => 42, 'T_BAR' => 23 ) );

        self::assertSame( array( 'T_FOO' => 42, 'T_BAR' => 23 ), $class->getConstants() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstantsReturnsAnArrayWithInheritClassConstants()
    {
        $parent = new StaticReflectionClass( __CLASS__, '', 0 );
        $parent->initConstants( array( 'T_FOO' => 42, 'T_BAR' => 23 ) );

        $child = new StaticReflectionClass( __CLASS__, '', 0 );
        $child->initParentClass( $parent );

        self::assertSame( array( 'T_FOO' => 42, 'T_BAR' => 23 ), $child->getConstants() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsExpectedSingleMethod()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( 'fooBar', '', 0 ) ) );

        self::assertEquals( 1, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsExpectedTwoMethods()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods(
            array(
                new StaticReflectionMethod( 'fooBar', '', 0 ),
                new StaticReflectionMethod( 'baz', '', 0 ),
            )
        );

        self::assertSame( 2, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsFromParentClass()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'bar', '', 0 ) ) );

        self::assertSame( 2, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsWithSameNameOnlyOneTime()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        self::assertSame( 1, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsWorksCaseInsensitive()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'FoO', '', 0 ) ) );

        self::assertSame( 1, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsInheritPublicMethods()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PUBLIC ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'bar', '', \ReflectionMethod::IS_PUBLIC ) ) );

        self::assertSame( 2, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsInheritProtectedMethods()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PROTECTED ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'bar', '', \ReflectionMethod::IS_PUBLIC ) ) );

        self::assertSame( 2, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsInheritPrivateMethods()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PRIVATE ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'bar', '', \ReflectionMethod::IS_PUBLIC ) ) );

        self::assertSame( 2, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsLastMethodInInheritanceHierarchy()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $methods = $class->getMethods();
        self::assertSame( $class, $methods[0]->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsConcreteMethodInsteadOfAbstract()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'foo', '', StaticReflectionMethod::IS_ABSTRACT ) ) );

        $methods = $class->getMethods();
        self::assertSame( $parent, $methods[0]->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsOfImplementedInterface()
    {
        $interface = new \ReflectionClass( 'Iterator' );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initInterfaces( array( $interface ) );

        self::assertEquals( 5, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetMethodsPrefersImplementedMethodsOverInterfaceMethods()
    {
        $interface = new \ReflectionClass( 'Iterator' );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods(
            array(
                new StaticReflectionMethod( 'current', '', 0 ),
                new StaticReflectionMethod( 'rewind', '', 0 ),
                new StaticReflectionMethod( 'valid', '', 0 ),
            )
        );
        $class->initInterfaces( array( $interface ) );

        $declaredMethodCount = 0;
        foreach ( $class->getMethods() as $method )
        {
            if ( $method->getDeclaringClass()->getName() === $class->getName() )
            {
                ++$declaredMethodCount;
            }
        }
        self::assertSame( 3, $declaredMethodCount );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstructorReturnsNullWhenNoConstructorExists()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertNull( $class->getConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetConstructorReturnsMethodNamedConstruct()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( '__construct', '', 0 ) ) );

        self::assertNotNull( $class->getConstructor() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPropertyReturnsDirectlyDeclaredProperty()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initProperties(
            array(
                $prop0 = new StaticReflectionProperty( 'foo', '', 0 ),
                $prop1 = new StaticReflectionProperty( 'bar', '', 0 ),
            )
        );

        self::assertSame( $prop1, $class->getProperty( 'bar' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPropertiesReturnsDirectlyDeclaredProperties()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initProperties(
            array(
                new StaticReflectionProperty( 'foo', '', 0 ),
                new StaticReflectionProperty( 'bar', '', 0 ),
            )
        );

        self::assertEquals( 2, count( $class->getProperties() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPropertiesReturnsDirectlyDeclaredPrivateProperties()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initProperties(
            array(
                new StaticReflectionProperty( 'foo', '', \ReflectionProperty::IS_PRIVATE ),
            )
        );

        self::assertEquals( 1, count( $class->getProperties() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPropertiesReturnsPublicOrProtectedInheritProperties()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initProperties(
            array(
                new StaticReflectionProperty( 'foo', '', \ReflectionProperty::IS_PROTECTED ),
                new StaticReflectionProperty( 'bar', '', \ReflectionProperty::IS_PUBLIC ),
            )
        );

        $child = new StaticReflectionClass( __CLASS__, '', 0 );
        $child->initParentClass( $parent );
        $child->initProperties( array(  new StaticReflectionProperty( 'baz', '', 0 ) ) );

        self::assertEquals( 3, count( $child->getProperties() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPropertiesNotReturnsPrivateInheritProperties()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initProperties(
            array(
                new StaticReflectionProperty( 'foo', '', \ReflectionProperty::IS_PRIVATE ),
                new StaticReflectionProperty( 'bar', '', \ReflectionProperty::IS_PUBLIC ),
            )
        );

        $child = new StaticReflectionClass( __CLASS__, '', 0 );
        $child->initParentClass( $parent );
        $child->initProperties( array(  new StaticReflectionProperty( 'baz', '', 0 ) ) );

        self::assertEquals( 2, count( $child->getProperties() ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testHasPropertyReturnsTrueForInheritProperty()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initProperties( array( $prop = new StaticReflectionProperty( 'foo', '', 0 ) ) );

        $child = new StaticReflectionClass( __CLASS__, '', 0 );
        $child->initParentClass( $parent );
        $child->initProperties( array(  new StaticReflectionProperty( 'baz', '', 0 ) ) );

        self::assertTrue( $child->hasProperty( 'foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetPropertyReturnsInheritProperty()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initProperties( array( $prop = new StaticReflectionProperty( 'foo', '', 0 ) ) );

        $child = new StaticReflectionClass( __CLASS__, '', 0 );
        $child->initParentClass( $parent );
        $child->initProperties( array(  new StaticReflectionProperty( 'baz', '', 0 ) ) );

        self::assertSame( $prop, $child->getProperty( 'foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticPropertiesReturnsExpectedKeyValueArray()
    {
        $prop0 = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_STATIC);
        $prop0->initValue( new StaticReflectionValue( 42 ) );

        $prop1 = new StaticReflectionProperty( 'bar', '', StaticReflectionProperty::IS_STATIC);

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initProperties( array( $prop0, $prop1 ) );

        self::assertEquals( array( 'foo' => 42, 'bar' => null ), $class->getStaticProperties() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticPropertyValueReturnsExpectedValue()
    {
        $prop = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_STATIC);
        $prop->initValue( new StaticReflectionValue( 42 ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initProperties( array( $prop ) );

        self::assertEquals( 42, $class->getStaticPropertyValue( 'foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticPropertyValueReturnsExpectedStaticReflectionValue()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );

        self::assertEquals( 42, $class->getStaticPropertyValue( 'foo', 42 ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testGetStaticPropertyValueReturnsNullEvenWhenStaticReflectionValueWasGiven()
    {
        $prop = new StaticReflectionProperty( 'foo', '', StaticReflectionProperty::IS_STATIC);
        $prop->initValue( new StaticReflectionValue( null ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initProperties( array( $prop ) );

        self::assertNull( $class->getStaticPropertyValue( 'foo', 42 ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstantiableReturnsTrueForClassWithoutConstructor()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertTrue( $class->isInstantiable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstantiableReturnsTrueForClassWithConstructor()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( '__construct', '', StaticReflectionMethod::IS_PUBLIC ) ) );

        self::assertTrue( $class->isInstantiable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstantiableReturnsFalseForClassWithProtectedConstructor()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( '__construct', '', StaticReflectionMethod::IS_PROTECTED ) ) );

        self::assertFalse( $class->isInstantiable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstantiableReturnsFalseForClassWithPrivateConstructor()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( '__construct', '', StaticReflectionMethod::IS_PRIVATE ) ) );

        self::assertFalse( $class->isInstantiable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsFalseForClassWithoutParent()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertFalse( $class->isSubclassOf( 'Foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsFalseForClassWithOtherParent()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( new StaticReflectionClass( 'Bar', '', 0 ) );

        self::assertFalse( $class->isSubclassOf( 'Foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsFalseForClassItSelf()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertFalse( $class->isSubclassOf( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsTrueForClassWithParent()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( new StaticReflectionClass( 'Foo', '', 0 ) );

        self::assertTrue( $class->isSubclassOf( 'Foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsTrueForClassWithParentWithParent()
    {
        $parentParent = new StaticReflectionClass( 'ParentParent', '', 0 );

        $parent = new StaticReflectionClass( 'Parent', '', 0 );
        $parent->initParentClass( $parentParent );

        $child = new StaticReflectionClass( __CLASS__, '', 0 );
        $child->initParentClass( $parent );

        self::assertTrue( $child->isSubclassOf( 'ParentParent' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsTrueForClassWithParentCaseInsensitive()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( new StaticReflectionClass( 'Foo', '', 0 ) );

        self::assertTrue( $class->isSubclassOf( 'FOO' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsTrueForClassWithParentAndLeadingBackslash()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( new StaticReflectionClass( 'Foo', '', 0 ) );

        self::assertTrue( $class->isSubclassOf( '\Foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsSubclassOfReturnsTrueForImplementedInterface()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initInterfaces( array( new StaticReflectionInterface( 'Foo', '' ) ) );

        self::assertTrue( $class->isSubclassOf( 'Foo' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstantiableReturnsFalseForClassWithAbstractConstructor()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( '__construct', '', StaticReflectionMethod::IS_ABSTRACT ) ) );

        self::assertFalse( $class->isInstantiable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInstantiableReturnsFalseForAbstractClass()
    {
        $class = new StaticReflectionClass( __CLASS__, '', StaticReflectionClass::IS_EXPLICIT_ABSTRACT );
        $class->initMethods( array( new StaticReflectionMethod( '__construct', '', StaticReflectionMethod::IS_PUBLIC ) ) );

        self::assertFalse( $class->isInstantiable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsIterateableReturnsFalseByDefault()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertFalse( $class->isIterateable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsIterateableReturnsTrueForClassThatImplementsTraversable()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initInterfaces( array( new StaticReflectionInterface( 'Traversable', '' ) ) );

        self::assertTrue( $class->isIterateable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsIterateableReturnsTrueForAbstractClassThatImplementsTraversable()
    {
        $class = new StaticReflectionClass( __CLASS__, '', StaticReflectionClass::IS_EXPLICIT_ABSTRACT );
        $class->initInterfaces( array( new StaticReflectionInterface( 'Traversable', '' ) ) );

        self::assertTrue( $class->isIterateable() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsAbstractReturnsFalseWhenModifierIsNotSet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertFalse( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsAbstractReturnsTrueWhenIsExplicitAbstractModifierIsSet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', StaticReflectionClass::IS_EXPLICIT_ABSTRACT );
        self::assertTrue( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsAbstractReturnsTrueWhenIsImplicitAbstractModifierIsSet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', StaticReflectionClass::IS_IMPLICIT_ABSTRACT );
        self::assertTrue( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsAbstractReturnsTrueWhenClassContainsAnAbstractMethod()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( 'foo', '', StaticReflectionMethod::IS_ABSTRACT ) ) );
        
        self::assertTrue( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsFinalReturnsFalseByDefault()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertFalse( $class->isFinal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsFinalReturnsTrueWhenFinalModifierIsSet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', \ReflectionClass::IS_FINAL );
        self::assertTrue( $class->isFinal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testIsInterfaceAlwaysReturnsFalse()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        self::assertFalse( $class->isInterface() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForClass()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );
        $class->initStartLine( 23 );
        $class->initEndLine( 42 );

        $actual   = $class->__toString();
        $expected = 'Class [ <user> class Foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [0] {' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [0] {'  . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [0] {' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForClassMarkedAsAbstract()
    {
        $class = new StaticReflectionClass( 'Foo', '', StaticReflectionClass::IS_EXPLICIT_ABSTRACT );
        $class->initFileName( '/bar/Foo.php' );
        $class->initStartLine( 23 );
        $class->initEndLine( 42 );

        $actual   = $class->__toString();
        $expected = 'Class [ <user> abstract class Foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [0] {' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [0] {'  . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [0] {' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForClassWithParent()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initParentClass( new StaticReflectionClass( 'Bar', '', 0 ) );
        $class->initFileName( '/bar/Foo.php' );
        $class->initStartLine( 23 );
        $class->initEndLine( 42 );

        $actual   = $class->__toString();
        $expected = 'Class [ <user> class Foo extends Bar ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [0] {' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [0] {'  . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [0] {' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForClassWithImplementedInterfaces()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initInterfaces( array( new StaticReflectionInterface( 'Bar', '', 0 ) ) );
        $class->initFileName( '/bar/Foo.php' );
        $class->initStartLine( 23 );
        $class->initEndLine( 42 );

        $actual   = $class->__toString();
        $expected = 'Class [ <user> class Foo implements Bar ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [0] {' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [0] {'  . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [0] {' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     */
    public function testToStringReturnsExpectedResultForClassWithProperties()
    {
        $class = new StaticReflectionClass( 'Foo', '', 0 );
        $class->initFileName( '/bar/Foo.php' );
        $class->initStartLine( 23 );
        $class->initEndLine( 42 );
        $class->initProperties(
            array(
                new StaticReflectionProperty( '_foo', '', StaticReflectionProperty::IS_PRIVATE )
            )
        );

        $actual   = $class->__toString();
        $expected = 'Class [ <user> class Foo ] {' . PHP_EOL .
                    '  @@ /bar/Foo.php 23-42' . PHP_EOL . PHP_EOL .
                    '  - Constants [0] {' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Properties [1] {'  . PHP_EOL .
                    '    Property [ <default> private $_foo ]' . PHP_EOL .
                    '  }' . PHP_EOL . PHP_EOL .
                    '  - Methods [0] {' . PHP_EOL .
                    '  }' . PHP_EOL .
                    '}';

        self::assertEquals( $expected, $actual );
    }


    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetPropertyThrowsExceptionWhenNoPropertyForTheGivenNameExists()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->getProperty( 'foo' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testGetStaticPropertyValueThrowsExceptionWhenNoPropertyForTheGivenNameExists()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->getStaticPropertyValue( 'foo' );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitParentClassThrowsLogicExceptionWhenParentWasAlreadySet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( new \ReflectionClass( __CLASS__ ) );
        $class->initParentClass( new \ReflectionClass( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\api\StaticReflectionClass
     * @group reflection
     * @group reflection::api
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitPropertiesThrowsLogicExceptionWhenPropertiesWasAlreadySet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initProperties( array() );
        $class->initProperties( array() );
    }
}