<?php
/**
 * This file is part of the static reflection component.
 *
 * PHP Version 5
 *
 * Copyright (c) 2008-2009, Manuel Pichler <mapi@pdepend.org>.
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
 * @package   org\pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection\api;

require_once 'PHPUnit/Framework.php';

require_once 'api/CompatibilityReflectionClassTest.php';
require_once 'api/CompatibilityReflectionInterfaceTest.php';
require_once 'api/CompatibilityReflectionMethodTest.php';
require_once 'api/CompatibilityReflectionParameterTest.php';
require_once 'api/CompatibilityReflectionPropertyTest.php';
require_once 'api/StaticReflectionClassTest.php';
require_once 'api/StaticReflectionInterfaceTest.php';
require_once 'api/StaticReflectionMethodTest.php';
require_once 'api/StaticReflectionParameterTest.php';
require_once 'api/StaticReflectionPropertyTest.php';

/**
 * Test suite for the static reflection api subpackage.
 *
 * @category  PHP
 * @package   org\pdepend\reflection\api
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
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
        $this->setName( 'org::pdepend::reflection::api::AllTests' );

        \PHPUnit_Util_Filter::addDirectoryToWhitelist(
            realpath( dirname( __FILE__ ) . '/../../source/' )
        );

        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionClassTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionInterfaceTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionMethodTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionParameterTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\StaticReflectionPropertyTest' );

        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionClassTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionInterfaceTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionMethodTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionParameterTest' );
        $this->addTestSuite( '\org\pdepend\reflection\api\CompatibilityReflectionPropertyTest' );
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