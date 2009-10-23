<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

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
     * @group compatibilitytest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionClass', StaticReflectionClass::TYPE );
    }
}