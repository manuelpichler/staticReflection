<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

require_once 'PHPUnit/Framework.php';

require_once 'ArgumentValidatorFactoryTest.php';
require_once 'ReflectionArgumentValidatorTest.php';
require_once 'DefaultContainerTest.php';
require_once 'ReflectionPropertyInjectionTest.php';
require_once 'ReflectionSetterInjectionTest.php';
require_once 'ObjectFactoryFactoryTest.php';
require_once 'ObjectBuilderFactoryTest.php';

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
        $this->setName( 'com::example::ioc::AllTests' );

        \PHPUnit_Util_Filter::addDirectoryToWhitelist(
            realpath( dirname( __FILE__ ) . '/../source/' )
        );

        $this->addTestSuite( 'de\buzz2ee\ioc\ArgumentValidatorFactoryTest' );
        $this->addTestSuite( 'de\buzz2ee\ioc\ReflectionArgumentValidatorTest' );
        $this->addTestSuite( 'de\buzz2ee\ioc\DefaultContainerTest' );
        $this->addTestSuite( 'de\buzz2ee\ioc\ReflectionPropertyInjectionTest' );
        $this->addTestSuite( 'de\buzz2ee\ioc\ReflectionSetterInjectionTest' );
        $this->addTestSuite( 'de\buzz2ee\ioc\ObjectBuilderFactoryTest' );
        $this->addTestSuite( 'de\buzz2ee\ioc\ObjectFactoryFactoryTest' );
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