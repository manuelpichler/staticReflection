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
     * @group compatibilitytest
     */
    public function testGetName()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 0 );

        $this->assertSame( $internal->getName(), $static->getName() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetPositionOfFirstParameter()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 0 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 0 );

        $this->assertSame( $internal->getPosition(), $static->getPosition() );
    }

    /**
     * @return void
     * @covers \ReflectionParameter
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetPositionOfLastParameter()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar', 2 );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar', 2 );

        $this->assertSame( $internal->getPosition(), $static->getPosition() );
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