<?php
namespace org\pdepend\reflection;

use org\pdepend\reflection\parser\ParserContext;
use org\pdepend\reflection\factories\StaticFactory;

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
                || $reflection->isUserDefined() !== $method->isUserDefined()
                || $reflection->isInternal() !== $method->isInternal()
                || is_int( strpos( $method->getDocComment(), '@access private' ) )
            ) {
                continue;
            }
            $methods[] = $method->getName();
        }
        sort( $methods );
        return $methods;
    }

    /**
     * Includes the searched class into the runtime scope.
     *
     * @param string $className Name of the searched class.
     *
     * void
     */
    protected function includeClass( $className )
    {
        $includePath = get_include_path();
        set_include_path( $includePath . PATH_SEPARATOR . __DIR__ . '/_source' );

        include_once $this->getPathnameForClass( $className );

        set_include_path( $includePath );
    }

    /**
     * This method will return the pathname of the source file for the given
     * class.
     *
     * @param string $className Name of the searched class.
     *
     * @return string
     */
    public function getPathnameForClass( $className )
    {
        $localName = explode( '\\', $className );
        $localName = array_pop( $localName );

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator( __DIR__ . '/_source' )
        );

        foreach ( $files as $file )
        {
            if ( pathinfo( $file->getFilename(), PATHINFO_FILENAME ) == $localName )
            {
                return $file->getRealpath();
            }
        }
        throw new \ErrorException( 'Cannot locate pathname for class: ' . $className );
    }

    protected function createParserContext()
    {
        $session  = new ReflectionSession();
        $resolver = $this->_createSourceResolver();

        $context = new ParserContext( $session, $resolver );
        $session->addFactory( new StaticFactory( $context ) );

        return $context;
    }

    private function _createSourceResolver()
    {
        $resolver = $this->getMock( 'org\pdepend\reflection\interfaces\SourceResolver' );
        $resolver->expects( $this->any() )
            ->method( 'getPathnameForClass' )
            ->will( $this->returnCallback( array( $this, 'getPathnameForClass' ) ) );
        $resolver->expects( $this->atLeastOnce() )
            ->method( 'getSourceForClass' )
            ->will( $this->returnCallback( array( $this, 'getSourceForClass' ) ) );
        return $resolver;
    }

    /**
     * This method will return the source code of the source file where the
     * given class is defined.
     *
     * @param string $className Name of the search class.
     *
     * @return string
     */
    public function getSourceForClass( $className )
    {
        return file_get_contents( $this->getPathnameForClass( $className ) );
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