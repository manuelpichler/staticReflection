<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\aop;

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Abstract base test case for the aop package.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Autoloading already initialized?
     *
     * @var boolean
     */
    private static $_initialized = false;

    /**
     * @var boolean
     */
    protected $backupStaticAttributes = false;

    /**
     * Registers the test autoloading.
     */
    public function __construct()
    {
        parent::__construct();

        if ( self::$_initialized === false )
        {
            self::$_initialized = true;
            spl_autoload_register( array( __CLASS__, 'autoload' ) );
        }
    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $visibility
     *
     * @return JoinPoint
     */
    protected function createJoinPoint( $className = null, $methodName = null, $visibility = null )
    {
        $joinPoint = $this->getMock( 'de\buzz2ee\aop\interfaces\JoinPoint' );
        $joinPoint->expects( $className === null ? $this->never() : $this->atLeastOnce() )
            ->method( 'getClassName' )
            ->will( $this->returnValue( $className ) );
        $joinPoint->expects( $methodName === null ? $this->never() : $this->atLeastOnce() )
            ->method( 'getMethodName' )
            ->will( $this->returnValue( $methodName ) );
        $joinPoint->expects( $visibility === null ? $this->never() : $this->atLeastOnce() )
            ->method( 'getVisibility' )
            ->will( $this->returnValue( $visibility ) );

        return $joinPoint;
    }

    /**
     * @param boolean $returnValue
     *
     * @return PointcutMatcher
     */
    protected function createPointcutMatcher( $returnValue )
    {
        $matcher = $this->getMock( '\de\buzz2ee\aop\interfaces\PointcutMatcher' );
        $matcher->expects( $this->any() )
            ->method( 'match' )
            ->will( $this->returnValue( $returnValue ) );

        return $matcher;
    }

    /**
     * @param string $className
     *
     * @return boolean
     */
    public static function autoload( $className )
    {
        if ( strpos( $className, __NAMESPACE__ ) !== 0 )
        {
            return false;
        }

        $filename = sprintf( '%s.php', strtr( substr( $className, 15 ), '\\', '/' ) );
        $pathname = sprintf( '%s/../source/%s', dirname( __FILE__ ), $filename );

        if ( file_exists( $pathname ) === false )
        {
            $pathname = sprintf( '%s/_source/%s', dirname( __FILE__ ), $filename );
        }
        if ( file_exists( $pathname ) === false )
        {
            return false;
        }

        include $pathname;
        return true;
    }
}