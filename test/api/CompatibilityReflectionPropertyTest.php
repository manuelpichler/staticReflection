<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace pdepend\reflection\api;

require_once 'BaseCompatibilityTest.php';

/**
 * Tests the api compatiblility between the static reflection implementation and
 * PHP's native api.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class CompatibilityReflectionPropertyTest extends BaseCompatibilityTest
{
    /**
     * @return void
     * @covers \ReflectionProperty
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionProperty', StaticReflectionProperty::TYPE );
    }

    /**
     * Creates an internal reflection property instance.
     *
     * @param string $className    Name of the searched class.
     * @param string $propertyName Name of the searched property.
     *
     * @return \ReflectionProperty
     */
    protected function createInternal( $className, $propertyName )
    {
        return $this->createInternalClass( $className )->getProperty( $propertyName );
    }

    /**
     * Creates a static reflection property instance.
     *
     * @param string $className    Name of the searched class.
     * @param string $propertyName Name of the searched property.
     *
     * @return \pdepend\reflection\api\StaticReflectionProperty
     */
    protected function createStatic( $className, $propertyName )
    {
        return $this->createStaticClass( $className )->getProperty( $propertyName );
    }
}