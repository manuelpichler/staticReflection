<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

require_once 'BaseTest.php';

/**
 * Tests the api compatiblility between the static reflection implementation and
 * PHP's native api.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class CompatibilityReflectionParameterTest extends BaseTest
{
    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionParameter
     * @group reflection
     * @group unittest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionParameter', StaticReflectionParameter::TYPE );
    }

}