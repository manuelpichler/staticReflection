<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace pdepend\reflection\api;

require_once __DIR__ . '/BaseCompatibilityTest.php';

/**
 * Tests the api compatiblility between the static reflection implementation and
 * PHP's native api.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class CompatibilityReflectionInterfaceTest extends BaseCompatibilityTest
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
        self::assertPublicApiEquals( 'ReflectionClass', StaticReflectionInterface::TYPE );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetDocCommentForInterfaceWithDocComment()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithDocComment' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithDocComment' );

        self::assertSame( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetDocCommentForInterfaceWithoutDocComment()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithoutDocComment' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithoutDocComment' );

        self::assertSame( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testInNamespaceForInterfaceWithNamespace()
    {
        $internal = $this->createInternalClass( '\foo\bar\CompatInterfaceWithNamespace' );
        $static   = $this->createStaticClass( '\foo\bar\CompatInterfaceWithNamespace' );

        self::assertSame( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testInNamespaceForInterfaceWithNamespaceDefault()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithNamespaceDefault' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithNamespaceDefault' );

        self::assertSame( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testInNamespaceForInterfaceWithoutNamespace()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithoutNamespace' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithoutNamespace' );

        self::assertSame( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testHasConstantThatExists()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithConstant' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithConstant' );

        self::assertSame(
            $internal->hasConstant( 'T_BAR' ),
            $static->hasConstant( 'T_BAR' )
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testHasConstantThatDoesNotExists()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithConstant' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithConstant' );

        self::assertEquals( $internal->hasConstant( 'T_FOO' ), $static->hasConstant( 'T_FOO' ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstantOnConstantThatDoesNotExist()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $static   = $this->createStaticClass( 'CompatInterfaceSimple' );

        self::assertEquals( $internal->getConstant( 'T_FOO' ), $static->getConstant( 'T_FOO' ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetModifiersContainsZendAccInterface()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $static   = $this->createStaticClass( 'CompatInterfaceSimple' );

        self::assertSame(
            StaticReflectionInterface::ZEND_ACC_INTERFACE,
            $internal->getModifiers() & $static->getModifiers()
        );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstructorForClassWithConstructMethod()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithConstruct' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithConstruct' );

        self::assertSame( $internal->getConstructor(), $static->getConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetConstructorForClassWithClassNameMethod()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithClassNameMethod' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithClassNameMethod' );

        self::assertSame( $internal->getConstructor(), $static->getConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetParentClass()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $static   = $this->createStaticClass( 'CompatInterfaceSimple' );

        self::assertSame( $internal->getParentClass(), $static->getParentClass() );
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
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $static   = $this->createStaticClass( 'CompatInterfaceSimple' );

        $expected = $this->executeFailingMethod( $internal, 'getProperty', 'foo' );
        $actual   = $this->executeFailingMethod( $static, 'getProperty', 'foo' );

        self::assertEquals( $expected, $actual );
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
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $static   = $this->createStaticClass( 'CompatInterfaceSimple' );

        $expected = $this->executeFailingMethod( $internal, 'getStaticPropertyValue', 'foo' );
        $actual   = $this->executeFailingMethod( $static, 'getStaticPropertyValue', 'foo' );

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticPropertyValueForUnknownPropertyWithStaticReflectionValue()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $static   = $this->createStaticClass( 'CompatInterfaceSimple' );

        self::assertEquals(
            $internal->getStaticPropertyValue( 'foo', 42 ),
            $static->getStaticPropertyValue( 'foo', 42 )
        );
    }
}