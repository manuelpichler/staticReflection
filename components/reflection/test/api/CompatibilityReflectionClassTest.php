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
    public function testGetParentClassForClassWithoutParent()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutParent' );
        $static   = $this->createStaticClass( 'CompatClassWithoutParent' );

        $this->assertEquals( $internal->getParentClass(), $static->getParentClass() );
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
    public function testGetPropertyForUnknownPropertyExceptionMessage()
    {
        $internal = $this->createInternalClass( 'CompatClassWithoutProperties' );
        $static   = $this->createStaticClass( 'CompatClassWithoutProperties' );

        $expected = $this->executeFailingMethod( $internal, 'getProperty', __FUNCTION__ );
        $actual   = $this->executeFailingMethod( $static, 'getProperty', __FUNCTION__ );;

        $this->assertEquals( $expected, $actual );
    }
}