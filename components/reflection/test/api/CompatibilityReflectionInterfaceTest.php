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
        $this->assertPublicApiEquals( 'ReflectionClass', StaticReflectionInterface::TYPE );
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

        $this->assertSame( $internal->getDocComment(), $static->getDocComment() );
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

        $this->assertSame( $internal->getDocComment(), $static->getDocComment() );
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

        $this->assertSame( $internal->inNamespace(), $static->inNamespace() );
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

        $this->assertSame( $internal->inNamespace(), $static->inNamespace() );
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

        $this->assertSame( $internal->inNamespace(), $static->inNamespace() );
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

        $this->assertSame(
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

        $this->assertEquals( $internal->hasConstant( 'T_FOO' ), $static->hasConstant( 'T_FOO' ) );
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

        $this->assertEquals( $internal->getConstant( 'T_FOO' ), $static->getConstant( 'T_FOO' ) );
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

        $this->assertSame(
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

        $this->assertSame( $internal->getConstructor(), $static->getConstructor() );
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

        $this->assertSame( $internal->getConstructor(), $static->getConstructor() );
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

        $this->assertSame( $internal->getParentClass(), $static->getParentClass() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetProperty()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $static   = $this->createStaticClass( 'CompatInterfaceSimple' );

        $expected = $this->executeFailingMethod( $internal, 'getProperty', 'foo' );
        $actual   = $this->executeFailingMethod( $static, 'getProperty', 'foo' );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetStaticPropertyValue()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceSimple' );
        $static   = $this->createStaticClass( 'CompatInterfaceSimple' );

        $expected = $this->executeFailingMethod( $internal, 'getStaticPropertyValue', 'foo' );
        $actual   = $this->executeFailingMethod( $static, 'getStaticPropertyValue', 'foo' );

        $this->assertEquals( $expected, $actual );
    }
}