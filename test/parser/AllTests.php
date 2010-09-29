<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace pdepend\reflection\parser;

require_once 'PHPUnit/Autoload.php';

require_once 'ParserTest.php';
require_once 'TokenizerTest.php';

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
        $this->setName( 'org::pdepend::reflection::parser::AllTests' );

        \PHP_CodeCoverage_Filter::getInstance()->addDirectoryToWhitelist(
            realpath( dirname( __FILE__ ) . '/../../source/' )
        );

        $this->addTestSuite( '\pdepend\reflection\parser\TokenizerTest' );
        $this->addTestSuite( '\pdepend\reflection\parser\ParserTest' );
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
