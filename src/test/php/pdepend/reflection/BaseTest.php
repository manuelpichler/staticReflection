<?php
/**
 * This file is part of the static reflection component.
 *
 * PHP Version 5
 *
 * Copyright (c) 2009-2011, Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  PHP
 * @package   pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection;

/**
 * Base test case for this component.
 *
 * @category  PHP
 * @package   pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Is autoloading already initialized?
     *
     * @var boolean
     */
    private static $_initialized = false;

    /**
     * List of methods that will be added to the list of expected object
     *
     * @var array(string)
     */
    protected $methodBackwardsCompatibilityList = array();

    /**
     * Constructs a new test instance.
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
     * Helper method to allow PHPUnit versions < 3.5.x
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertInstanceOf( $expected, $actual, $message = '' )
    {
        if ( is_callable( get_parent_class( __CLASS__ ) . '::' ) . __FUNCTION__ )
        {
            return parent::assertInstanceOf( $expected, $actual, $message );
        }
        return parent::assertType( $expected, $actual, $message );
    }

    /**
     * Helper method to allow PHPUnit versions < 3.5.x
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertInternalType( $expected, $actual, $message = '' )
    {
        if ( is_callable( get_parent_class( __CLASS__ ) . '::' ) . __FUNCTION__ )
        {
            return parent::assertInternalType( $expected, $actual, $message );
        }
        return parent::assertType( $expected, $actual, $message );
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

        self::assertEquals( $expected, $actual );
    }

    /**
     * @param string $className
     *
     * @return array(string)
     */
    protected function getPublicMethods( $className )
    {
        $phpversion =

        $reflection = new \ReflectionClass( $className );

        $methods = array();
        foreach ( $reflection->getMethods( \ReflectionMethod::IS_PUBLIC ) as $method )
        {
            $comment = $method->getDocComment();

            if ( !$method->isPublic()
                || $method->isStatic()
                || $reflection->isUserDefined() !== $method->isUserDefined()
                || $reflection->isInternal() !== $method->isInternal()
                || is_int( strpos( $comment, '@access private' ) )
            ) {
                continue;
            }

            $regexp = '(@since\s+PHP (\d+\.\d+\.\d+(\-dev|RC\d+|alpha\d+|beta\d+)?))';
            if (preg_match( $regexp, $comment, $match )
                && version_compare( phpversion(), $match[1] ) < 0
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
        set_include_path( $includePath . PATH_SEPARATOR . __DIR__ . '/../../../resources/files' );

        include_once $this->getPathnameForClass( $className );

        set_include_path( $includePath );
    }

    /**
     * This method will return <b>true</b> a source file for the given class
     * name exists.
     *
     * @param string $className Name of the searched class.
     *
     * @return string
     */
    public function hasPathnameForClass( $className )
    {
        $localName = explode( '\\', $className );
        $localName = array_pop( $localName );

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator( __DIR__ . '/../../../resources/files' )
        );

        foreach ( $files as $file )
        {
            if ( pathinfo( $file->getFilename(), PATHINFO_FILENAME ) == $localName )
            {
                return true;
            }
        }
        return false;
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
            new \RecursiveDirectoryIterator( __DIR__ . '/../../../resources/files' )
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

    /**
     * Will trigger an additional parsing process for the given class.
     *
     * @param string $className Name of the searched class.
     *
     * @return \ReflectionClass
     */
    public function getClassByName( $className )
    {
        $parser  = new parser\Parser( $this->createContext() );
        $classes = $parser->parseFile( $this->getPathnameForClass( $className ) );

        foreach ( $classes as $class )
        {
            if ( $class->getName() === $className )
            {
                return $class;
            }
        }
    }

    /**
     * Creates a mocked reflection factory instance.
     *
     * @return \pdepend\reflection\interfaces\ReflectionClassFactory
     */
    protected function createFactory()
    {
        $factory = $this->getMock( 'pdepend\reflection\interfaces\ReflectionClassFactory' );
        $factory->expects( $this->any() )
            ->method( 'createClass' )
            ->will( $this->returnCallback( array( $this, 'getClassByName' ) ) );

        return $factory;
    }

    /**
     * Creates a mocked parser context instance.
     *
     * @return \pdepend\reflection\interfaces\ParserContext
     */
    protected function createContext()
    {
        $session = $this->getMock( 'pdepend\reflection\interfaces\ParserContext' );
        $session->expects( $this->any() )
            ->method( 'getClassReference' )
            ->will( $this->returnCallback( array( $this, 'getClassByName' ) ) );

        return $session;
    }

    /**
     * Creates a mocked source resolver instance.
     *
     * @return \pdepend\reflection\interfaces\SourceResolver
     */
    protected function createResolver()
    {
        $resolver = $this->getMock( 'pdepend\reflection\interfaces\SourceResolver' );
        $resolver->expects( $this->any() )
            ->method( 'getPathnameForClass' )
            ->will( $this->returnCallback( array( $this, 'getPathnameForClass' ) ) );
        
        return $resolver;
    }

    /**
     * Creates a mocked reflection session instance
     *
     * @return \pdepend\reflection\ReflectionSession
     */
    protected function createSession()
    {
        return $this->getMock( 'pdepend\reflection\ReflectionSession' );
    }

    public static function autoload( $className )
    {
        if ( strpos( $className, __NAMESPACE__ ) !== 0 )
        {
            return;
        }

        $filename = sprintf( '%s.php', strtr( $className, '\\', '/' ) );
        $pathname = sprintf( '%s/../../../../main/php/%s', __DIR__, $filename );

        if ( file_exists( $pathname ) === false )
        {
            $pathname = sprintf( '%s/../../../resources/files/%s', __DIR__, $filename );
        }
        if ( file_exists( $pathname ) === false )
        {
            return false;
        }

        include $pathname;
        return true;
    }
}
