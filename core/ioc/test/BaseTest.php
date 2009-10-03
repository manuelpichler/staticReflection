<?php
namespace com\example\ioc;

require_once 'PHPUnit/Framework/TestCase.php';

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    private static $_initialized = false;

    public function __construct()
    {
        parent::__construct();

        if ( self::$_initialized === false )
        {
            self::$_initialized = true;
            spl_autoload_register( array( __CLASS__, 'autoload' ) );
        }
    }

    public static function autoload( $className )
    {
        if ( strpos( $className, __NAMESPACE__ ) !== 0 )
        {
            return;
        }

        $filename = sprintf( '%s.php', strtr( substr( $className, 16 ), '\\', '/' ) );
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