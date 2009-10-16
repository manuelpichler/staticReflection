<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\aop\pointcut;

require_once 'PHPUnit/Framework.php';

require_once 'PointcutAndMatcherTest.php';
require_once 'PointcutExecutionMatcherTest.php';
require_once 'PointcutExpressionParserTest.php';
require_once 'PointcutMatcherFactoryTest.php';
require_once 'PointcutNotMatcherTest.php';
require_once 'PointcutOrMatcherTest.php';

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
        $this->setName( 'de::buzz2ee::aop::pointcut::AllTests' );

        \PHPUnit_Util_Filter::addDirectoryToWhitelist(
            realpath( dirname( __FILE__ ) . '/../../source/' )
        );

        $this->addTestSuite( '\de\buzz2ee\aop\pointcut\PointcutAndMatcherTest' );
        $this->addTestSuite( '\de\buzz2ee\aop\pointcut\PointcutExecutionMatcherTest' );
        $this->addTestSuite( '\de\buzz2ee\aop\pointcut\PointcutExpressionParserTest' );
        $this->addTestSuite( '\de\buzz2ee\aop\pointcut\PointcutMatcherFactoryTest' );
        $this->addTestSuite( '\de\buzz2ee\aop\pointcut\PointcutNotMatcherTest' );
        $this->addTestSuite( '\de\buzz2ee\aop\pointcut\PointcutOrMatcherTest' );
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