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