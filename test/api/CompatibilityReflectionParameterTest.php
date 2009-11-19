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
class CompatibilityReflectionParameterTest extends BaseCompatibilityTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\api\StaticReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionParameter', StaticReflectionParameter::TYPE );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetName()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 0 );

        $this->assertEquals( $internal->getName(), $static->getName() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetPositionOfFirstParameter()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 0 );

        $this->assertEquals( $internal->getPosition(), $static->getPosition() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetPositionOfLastParameter()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 2 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 2 );

        $this->assertEquals( $internal->getPosition(), $static->getPosition() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsArrayForParameterWithoutTypeHint()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 0 );

        $this->assertSame( $internal->isArray(), $static->isArray() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsOptionalForParameterWithoutStaticReflectionValue()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 0 );

        $this->assertSame( $internal->isOptional(), $static->isOptional() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsOptionalForParameterWithStaticReflectionValue()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 1 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 1 );

        $this->assertSame( $internal->isOptional(), $static->isOptional() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsOptionalForParameterWithStaticReflectionValueAndNotOptionalFollowingParameter()
    {
        $internal = $this->createInternal( 'CompatParametersOptional', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParametersOptional', 'fooBar', 0 );

        $this->assertSame( $internal->isOptional(), $static->isOptional() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsOptionalForParameterWithStaticReflectionValueAndOptionalFollowingParameter()
    {
        $internal = $this->createInternal( 'CompatParametersOptional', 'fooBar', 2 );
        $static   = $this->createStatic( 'CompatParametersOptional', 'fooBar', 2 );

        $this->assertSame( $internal->isOptional(), $static->isOptional() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsOptionalForParameterWithNullStaticReflectionValue()
    {
        $internal = $this->createInternal( 'CompatParametersOptional', 'fooBar', 2 );
        $static   = $this->createStatic( 'CompatParametersOptional', 'fooBar', 2 );

        $this->assertSame( $internal->isOptional(), $static->isOptional() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetClassForParameterWithoutTypeHint()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 0 );

        $this->assertSame( $internal->getClass(), $static->getClass() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testgetDefaultValueForMagicConstantFunction()
    {
        $internal = $this->createInternal( '\compat\CompatParameterMagicConstantFunction', 'fooBar', 0 );
        $static   = $this->createStatic( '\compat\CompatParameterMagicConstantFunction', 'fooBar', 0 );

        $this->assertEquals( $internal->getDefaultValue(), $static->getDefaultValue() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testgetDefaultValueForMagicConstantMethod()
    {
        $internal = $this->createInternal( '\compat\CompatParameterMagicConstantMethod', 'fooBar', 0 );
        $static   = $this->createStatic( '\compat\CompatParameterMagicConstantMethod', 'fooBar', 0 );

        $this->assertEquals( $internal->getDefaultValue(), $static->getDefaultValue() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testgetDefaultValueForParameterWithoutDefaultExceptionMessage()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 0 );

        $expected = $this->executeFailingMethod( $internal, 'getDefaultValue' );
        $actual   = $this->executeFailingMethod( $static, 'getDefaultValue' );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testAllowsNullForParameterWithoutTypeHint()
    {
        $internal = $this->createInternal( 'CompatParameterAllowsNull', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameterAllowsNull', 'fooBar', 0 );

        $this->assertEquals( $internal->allowsNull(), $static->allowsNull() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testAllowsNullForParameterWithArrayTypeHint()
    {
        $internal = $this->createInternal( 'CompatParameterAllowsNull', 'fooBar', 1 );
        $static   = $this->createStatic( 'CompatParameterAllowsNull', 'fooBar', 1 );

        $this->assertEquals( $internal->allowsNull(), $static->allowsNull() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testAllowsNullForParameterWithClassTypeHint()
    {
        $internal = $this->createInternal( 'CompatParameterAllowsNull', 'fooBar', 2 );
        $static   = $this->createStatic( 'CompatParameterAllowsNull', 'fooBar', 2 );

        $this->assertEquals( $internal->allowsNull(), $static->allowsNull() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testAllowsNullForParameterWithArrayTypeHintAndStaticReflectionValue()
    {
        $internal = $this->createInternal( 'CompatParameterAllowsNull', 'fooBar', 3 );
        $static   = $this->createStatic( 'CompatParameterAllowsNull', 'fooBar', 3 );

        $this->assertEquals( $internal->allowsNull(), $static->allowsNull() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testAllowsNullForParameterWithArrayTypeHintAndNullStaticReflectionValue()
    {
        $internal = $this->createInternal( 'CompatParameterAllowsNull', 'fooBar', 4 );
        $static   = $this->createStatic( 'CompatParameterAllowsNull', 'fooBar', 4 );

        $this->assertEquals( $internal->allowsNull(), $static->allowsNull() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testAllowsNullForParameterWithClassTypeHintAndNullStaticReflectionValue()
    {
        $internal = $this->createInternal( 'CompatParameterAllowsNull', 'fooBar', 5 );
        $static   = $this->createStatic( 'CompatParameterAllowsNull', 'fooBar', 5 );

        $this->assertEquals( $internal->allowsNull(), $static->allowsNull() );
    }

    /**
     * Creates an internal reflection parameter instance.
     *
     * @param string  $className  Name of the searched class.
     * @param string  $methodName Name of the declaring method.
     * @param integer $position   The parameter position.
     *
     * @return \ReflectionParameter
     */
    protected function createInternal( $className, $methodName, $position )
    {
        $params = $this->createInternalClass( $className )
            ->getMethod( $methodName )
            ->getParameters();
        return $params[$position];
    }

    /**
     * Creates a static reflection parameter instance.
     *
     * @param string  $className  Name of the searched class.
     * @param string  $methodName Name of the declaring method.
     * @param integer $position   The parameter position.
     *
     * @return \org\pdepend\reflection\api\StaticReflectionParameter
     */
    protected function createStatic( $className, $methodName, $position )
    {
        $params = $this->createStaticClass( $className )
            ->getMethod( $methodName )
            ->getParameters();
        return $params[$position];
    }
}