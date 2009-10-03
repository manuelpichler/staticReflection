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
        include sprintf(
            '%s/../source/%s.php',
            dirname( __FILE__ ),
            strtr( substr( $className, 16 ), '\\', '/' )
        );
    }
}