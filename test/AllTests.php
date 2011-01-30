<?php
/**
 * This file is part of the static reflection component.
 *
 * PHP Version 5
 *
 * Copyright (c) 2009-2010, Manuel Pichler <mapi@pdepend.org>.
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
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection;

require_once __DIR__ . '/api/AllTests.php';
require_once __DIR__ . '/factories/AllTests.php';
require_once __DIR__ . '/parser/AllTests.php';
require_once __DIR__ . '/queries/AllTests.php';
require_once __DIR__ . '/resolvers/AllTests.php';

require_once __DIR__ . '/AutoloaderTest.php';
require_once __DIR__ . '/ReflectionSessionTest.php';
require_once __DIR__ . '/ReflectionSessionInstanceTest.php';
require_once __DIR__ . '/ReflectionClassCacheTest.php';
require_once __DIR__ . '/ReflectionClassProxyTest.php';
require_once __DIR__ . '/ReflectionClassProxyContextTest.php';

require_once __DIR__ . '/integration/AllTests.php';
require_once __DIR__ . '/regression/AllTests.php';

/**
 * Main component test suite
 *
 * @category  PHP
 * @package   pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class AllTests extends \PHPUnit_Framework_TestSuite
{
    /**
     * Constructs a new test suite instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setName( '\pdepend\reflection\AllTests' );

        \PHP_CodeCoverage_Filter::getInstance()->addDirectoryToWhitelist(
            realpath( dirname( __FILE__ ) . '/../source/' )
        );

        $this->addTestSuite( '\pdepend\reflection\AutoloaderTest' );
        $this->addTestSuite( '\pdepend\reflection\ReflectionClassProxyTest' );
        $this->addTestSuite( '\pdepend\reflection\ReflectionClassCacheTest' );
        $this->addTestSuite( '\pdepend\reflection\ReflectionClassProxyContextTest' );

        $this->addTest( api\AllTests::suite() );
        $this->addTest( factories\AllTests::suite() );
        $this->addTest( parser\AllTests::suite() );
        $this->addTest( queries\AllTests::suite() );
        $this->addTest( resolvers\AllTests::suite() );
/*
        $this->addTestSuite( '\pdepend\reflection\ReflectionSessionTest' );
        $this->addTestSuite( '\pdepend\reflection\ReflectionSessionInstanceTest' );

        $this->addTest( integration\AllTests::suite() );
        $this->addTest( regression\AllTests::suite() );
*/
    }

    /**
     * Returns a test suite instance.
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        return new \pdepend\reflection\AllTests();
    }
}
