<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection;

require_once 'PHPUnit/Framework.php';

require_once 'parser/AllTests.php';

require_once 'CompatibilityReflectionClassTest.php';
require_once 'CompatibilityReflectionInterfaceTest.php';
require_once 'CompatibilityReflectionMethodTest.php';
require_once 'CompatibilityReflectionParameterTest.php';
require_once 'StaticReflectionClassTest.php';
require_once 'StaticReflectionInterfaceTest.php';
require_once 'StaticReflectionMethodTest.php';
require_once 'StaticReflectionParameterTest.php';
require_once 'StaticReflectionPropertyTest.php';

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

        $this->addTestSuite( '\org\pdepend\reflection\CompatibilityReflectionClassTest' );
        $this->addTestSuite( '\org\pdepend\reflection\CompatibilityReflectionInterfaceTest' );
        $this->addTestSuite( '\org\pdepend\reflection\CompatibilityReflectionMethodTest' );
        $this->addTestSuite( '\org\pdepend\reflection\CompatibilityReflectionParameterTest' );
        $this->addTestSuite( '\org\pdepend\reflection\StaticReflectionClassTest' );
        $this->addTestSuite( '\org\pdepend\reflection\StaticReflectionInterfaceTest' );
        $this->addTestSuite( '\org\pdepend\reflection\StaticReflectionMethodTest' );
        $this->addTestSuite( '\org\pdepend\reflection\StaticReflectionParameterTest' );
        $this->addTestSuite( '\org\pdepend\reflection\StaticReflectionPropertyTest' );
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