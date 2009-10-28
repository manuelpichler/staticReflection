<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection;

require_once 'PHPUnit/Framework.php';

require_once 'parser/AllTests.php';

require_once 'api/CompatibilityReflectionClassTest.php';
require_once 'api/CompatibilityReflectionInterfaceTest.php';
require_once 'api/CompatibilityReflectionMethodTest.php';
require_once 'api/CompatibilityReflectionParameterTest.php';
require_once 'api/CompatibilityReflectionPropertyTest.php';
require_once 'api/StaticReflectionClassTest.php';
require_once 'api/StaticReflectionInterfaceTest.php';
require_once 'api/StaticReflectionMethodTest.php';
require_once 'api/StaticReflectionParameterTest.php';
require_once 'api/StaticReflectionPropertyTest.php';

/**
 * Main test suite.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class AllTests extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs a new test suite instance.
     */
    public function __construct()
    {
        $this->setName( 'org::pdepend::reflection::AllTests' );

        \PHPUnit_Util_Filter::addDirectoryToWhitelist(
            realpath( dirname( __FILE__ ) . '/../source/' )
        );

        $this->addTest( parser\AllTests::suite() );

        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionClassTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionInterfaceTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionMethodTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionParameterTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionPropertyTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionClassTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionInterfaceTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionMethodTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionParameterTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionPropertyTest' );
    }

    /**
     * Returns a test suite instance.
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        return new AllTests();
    }
}