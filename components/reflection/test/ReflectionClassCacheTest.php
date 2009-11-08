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
 * @package   org\pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection;

require_once 'BaseTest.php';

/**
 * Test case for the reflection class cache.
 *
 * @category  PHP
 * @package   org\pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionClassCacheTest extends BaseTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassCache
     * @group reflection
     * @group unittest
     */
    public function testHasReturnsFalseWhenNoMatchingClassExists()
    {
        $cache = new ReflectionClassCache();
        $this->assertFalse( $cache->has( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassCache
     * @group reflection
     * @group unittest
     */
    public function testHasReturnsTrueWhenMatchingClassExists()
    {
        $cache = new ReflectionClassCache();
        $cache->store( new \ReflectionClass( __CLASS__ ) );

        $this->assertTrue( $cache->has( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassCache
     * @group reflection
     * @group unittest
     */
    public function testRestoreReturnsPreviousRegisteredClassInstance()
    {
        $class = new \ReflectionClass( __CLASS__ );
        
        $cache = new ReflectionClassCache();
        $cache->store( $class );

        $this->assertSame( $class, $cache->restore( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\ReflectionClassCache
     * @group reflection
     * @group unittest
     * @expectedException \LogicException
     */
    public function testRestoreThrowsLogicExceptionWhenNoMatchingClassExists()
    {
        $cache = new ReflectionClassCache();
        $cache->restore( __CLASS__ );
    }
}