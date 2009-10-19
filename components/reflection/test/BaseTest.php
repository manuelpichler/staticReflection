<?php
namespace de\buzz2ee\reflection;

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

    /**
     * Asserts that the public api of the given classes is equal.
     *
     * @param string $classExpected
     * @param string $classActual
     *
     * @return void
     */
    protected function assertPublicApiEquals( $classExpected, $classActual )
    {
        $expected = $this->getPublicMethods( $classExpected );
        $actual   = $this->getPublicMethods( $classActual );

        $this->assertEquals( $expected, $actual );
    }

    /**
     * @param string $className
     *
     * @return array(string)
     */
    protected function getPublicMethods( $className )
    {
        $reflection = new \ReflectionClass( $className );

        $methods = array();
        foreach ( $reflection->getMethods( \ReflectionMethod::IS_PUBLIC ) as $method )
        {
            if ( !$method->isPublic()
                || $method->isStatic()
                || $method->getDeclaringClass()->getName() !== $className
                || is_int( strpos( $method->getDocComment(), '@access private' ) )
            ) {
                continue;
            }
            $methods[] = $method->getName();
        }
        sort( $methods );
        return $methods;
    }

    public static function autoload( $className )
    {
        if ( strpos( $className, __NAMESPACE__ ) !== 0 )
        {
            return;
        }

        $filename = sprintf( '%s.php', strtr( substr( $className, strlen( __NAMESPACE__ ) + 1 ), '\\', '/' ) );
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