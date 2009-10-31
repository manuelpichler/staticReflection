<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\api;

require_once 'BaseCompatibilityTest.php';

/**
 * Tests the api compatiblility between the static reflection implementation and
 * PHP's native api.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class CompatibilityReflectionClassTest extends BaseCompatibilityTest
{
    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionClass', StaticReflectionClass::TYPE );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsInstantiableForClassWithoutConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithoutConstructor' );

        $this->assertEquals( $internal->isInstantiable(), $static->isInstantiable() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsInstantiableForClassWithConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithConstructor' );

        $this->assertEquals( $internal->isInstantiable(), $static->isInstantiable() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsInstantiableForClassWithAbstractConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithAbstractConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithAbstractConstructor' );

        $this->assertEquals( $internal->isInstantiable(), $static->isInstantiable() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsInstantiableForClassWithProtectedConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithProtectedConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithProtectedConstructor' );

        $this->assertSame( $internal->isInstantiable(), $static->isInstantiable() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsInstantiableForClassWithPrivateConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithPrivateConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithPrivateConstructor' );

        $this->assertSame( $internal->isInstantiable(), $static->isInstantiable() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsSubclassOfForClassWithoutParent()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutParent' );
        $static   = $this->createStaticClass( 'CompatClassWithoutParent' );

        $this->assertSame(
            $internal->isSubclassOf( 'stdClass' ),
            $static->isSubclassOf( 'stdClass' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsSubclassOfOnClassItself()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutParent' );
        $static   = $this->createStaticClass( 'CompatClassWithoutParent' );

        $this->assertSame(
            $internal->isSubclassOf( 'CompatClassWithoutParent' ),
            $static->isSubclassOf( 'CompatClassWithoutParent' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsSubclassOfOnParentClass()
    {
        $internal = $this->createInternalClass( 'CompatClassWithParent' );
        $static   = $this->createStaticClass( 'CompatClassWithParent' );

        $this->assertSame(
            $internal->isSubclassOf( 'CompatClassWithoutParent' ),
            $static->isSubclassOf( 'CompatClassWithoutParent' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsSubclassOfOnParentClassCaseInsensitive()
    {
        $internal = $this->createInternalClass( 'CompatClassWithParent' );
        $static   = $this->createStaticClass( 'CompatClassWithParent' );

        $this->assertSame(
            $internal->isSubclassOf( 'compatCLASSWithoutPARENT' ),
            $static->isSubclassOf( 'compatCLASSWithoutPARENT' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsSubclassOfOnParentClassWithLeadingBackslash()
    {
        $internal = $this->createInternalClass( 'CompatClassWithParent' );
        $static   = $this->createStaticClass( 'CompatClassWithParent' );

        $this->assertSame(
            $internal->isSubclassOf( '\CompatClassWithoutParent' ),
            $static->isSubclassOf( '\CompatClassWithoutParent' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsSubclassOfOnImplementedInterface()
    {
        $internal = $this->createInternalClass( 'CompatClassWithImplementedInterface' );
        $static   = $this->createStaticClass( 'CompatClassWithImplementedInterface' );

        $this->assertSame(
            $internal->isSubclassOf( 'CompatInterfaceSimple' ),
            $static->isSubclassOf( 'CompatInterfaceSimple' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetParentClassForClassWithoutParent()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutParent' );
        $static   = $this->createStaticClass( 'CompatClassWithoutParent' );

        $this->assertSame( $internal->getParentClass(), $static->getParentClass() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetParentClassForClassWithParent()
    {
        $internal = $this->createInternalClass( 'CompatClassWithParent' );
        $static   = $this->createStaticClass( 'CompatClassWithParent' );

        $this->assertEquals(
            $internal->getParentClass()->getName(),
            $static->getParentClass()->getName()
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetMethodsWithProtectedFilterAndOverwrittenPublicMethod()
    {
        $internal = $this->createInternalClass( 'CompatClassWithOverwritingPublicMethod' );
        $static   = $this->createStaticClass( 'CompatClassWithOverwritingPublicMethod' );

        $this->assertEquals(
            count( $internal->getMethods( \ReflectionMethod::IS_PROTECTED ) ),
            count( $static->getMethods( \ReflectionMethod::IS_PROTECTED ) )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetMethodsReturnsInheritProtectedMethods()
    {
        $internal = $this->createInternalClass( 'CompatClassWithInheritProtectedMethod' );
        $static   = $this->createStaticClass( 'CompatClassWithInheritProtectedMethod' );

        $this->assertEquals( count( $internal->getMethods() ), count( $static->getMethods() ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetMethodsReturnsInheritPrivateMethods()
    {
        $internal = $this->createInternalClass( 'CompatClassWithInheritPrivateMethod' );
        $static   = $this->createStaticClass( 'CompatClassWithInheritPrivateMethod' );

        $this->assertEquals( count( $internal->getMethods() ), count( $static->getMethods() ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstructorForClassWithoutConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithoutConstructor' );

        $this->assertSame( $internal->getConstructor(), $static->getConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstructorForClassWithConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithConstructor' );

        $this->assertEquals(
            $internal->getConstructor()->getName(),
            $static->getConstructor()->getName()
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstructorForClassWithPrivateConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithPrivateConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithPrivateConstructor' );

        $this->assertEquals(
            $internal->getConstructor()->getName(),
            $static->getConstructor()->getName()
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstructorForClassWithAbstractConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithAbstractConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithAbstractConstructor' );

        $this->assertEquals(
            $internal->getConstructor()->getName(),
            $static->getConstructor()->getName()
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstructorForClassWithInheritConstructor()
    {
        $internal = $this->createInternalClass( 'CompatClassWithInheritConstructor' );
        $static   = $this->createStaticClass( 'CompatClassWithInheritConstructor' );

        $this->assertEquals(
            $internal->getConstructor()->getName(),
            $static->getConstructor()->getName()
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetProperties()
    {
        $internal = $this->createInternalClass( 'CompatClassWithProperties' );
        $static   = $this->createStaticClass( 'CompatClassWithProperties' );

        $this->assertEquals( count( $internal->getProperties() ), count( $static->getProperties() ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetPropertiesOnInheritProperties()
    {
        $internal = $this->createInternalClass( 'CompatClassWithParentProperties' );
        $static   = $this->createStaticClass( 'CompatClassWithParentProperties' );

        $this->assertEquals( count( $internal->getProperties() ), count( $static->getProperties() ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticProperties()
    {
        $internal = $this->createInternalClass( 'CompatClassWithStaticProperties' );
        $static   = $this->createStaticClass( 'CompatClassWithStaticProperties' );

        $this->assertEquals( $internal->getStaticProperties(), $static->getStaticProperties() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticPropertyValueForKnownProperty()
    {
        $internal = $this->createInternalClass( 'CompatClassWithStaticProperties' );
        $static   = $this->createStaticClass( 'CompatClassWithStaticProperties' );

        $this->assertEquals( 
            $internal->getStaticPropertyValue( 'foo' ),
            $static->getStaticPropertyValue( 'foo' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticPropertyValueForNullPropertyWithDefaultValue()
    {
        $internal = $this->createInternalClass( 'CompatClassWithStaticProperties' );
        $static   = $this->createStaticClass( 'CompatClassWithStaticProperties' );

        $this->assertEquals(
            $internal->getStaticPropertyValue( 'baz', 42 ),
            $static->getStaticPropertyValue( 'baz', 42 )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetPropertyForUnknownPropertyExceptionMessage()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutProperties' );
        $static   = $this->createStaticClass( 'CompatClassWithoutProperties' );

        $expected = $this->executeFailingMethod( $internal, 'getProperty', __FUNCTION__ );
        $actual   = $this->executeFailingMethod( $static, 'getProperty', __FUNCTION__ );;

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticPropertyValueForUnknownPropertyExceptionMessage()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutProperties' );
        $static   = $this->createStaticClass( 'CompatClassWithoutProperties' );

        $expected = $this->executeFailingMethod( $internal, 'getStaticPropertyValue', 'foo' );
        $actual   = $this->executeFailingMethod( $static, 'getStaticPropertyValue', 'foo' );

        $this->assertEquals( $expected, $actual );
    }
}