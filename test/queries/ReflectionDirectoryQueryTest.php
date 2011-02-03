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
 * @package   pdepend\reflection\queries
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection\queries;

require_once __DIR__ . '/ReflectionQueryTest.php';

/**
 * Test cases for the reflection directory query.
 *
 * @category  PHP
 * @package   pdepend\reflection\queries
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionDirectoryQueryTest extends ReflectionQueryTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\queries\ReflectionQuery
     * @covers \pdepend\reflection\queries\ReflectionDirectoryQuery
     * @group reflection
     * @group reflection::queries
     * @group unittest
     */
    public function testfindReturnsExpectedIteratorOfClasses()
    {
        $query  = new ReflectionDirectoryQuery( $this->createContext() );
        $result = $query->find( dirname( $this->getPathnameForClass( 'QueryClass' ) ) );

        self::assertEquals( 1, count( $result ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\queries\ReflectionQuery
     * @covers \pdepend\reflection\queries\ReflectionDirectoryQuery
     * @group reflection
     * @group reflection::queries
     * @group unittest
     */
    public function testfindReturnsExpectedEmptyIterator()
    {
        $query = new ReflectionDirectoryQuery( $this->createContext() );
        $query->exclude( '(Query([a-z]+)\.php)i' );

        $result = $query->find( dirname( $this->getPathnameForClass( 'QueryClass' ) ) );

        self::assertEquals( 0, count( $result ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\queries\ReflectionQuery
     * @covers \pdepend\reflection\queries\ReflectionDirectoryQuery
     * @group reflection
     * @group reflection::queries
     * @group unittest
     */
    public function testfindReturnsExpectedIteratorOfClassesForDirectorySymlink()
    {
        $link = $this->createSymlink( dirname( $this->getPathnameForClass( 'QueryClass' ) ) );

        $query  = new ReflectionDirectoryQuery( $this->createContext() );
        $result = $query->find( $link );

        self::assertEquals( 1, count( $result ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\queries\ReflectionQuery
     * @covers \pdepend\reflection\queries\ReflectionDirectoryQuery
     * @group reflection
     * @group reflection::queries
     * @group unittest
     * @expectedException \LogicException
     */
    public function testfindThrowsExceptionWhenGivenDirectoryIsFile()
    {
        $query  = new ReflectionDirectoryQuery( $this->createContext() );
        $query->find( __FILE__ );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\queries\ReflectionQuery
     * @covers \pdepend\reflection\queries\ReflectionDirectoryQuery
     * @group reflection
     * @group reflection::queries
     * @group unittest
     * @expectedException \LogicException
     */
    public function testfindThrowsExceptionWhenGivenDirectoryNotExists()
    {
        $query  = new ReflectionDirectoryQuery( $this->createContext() );
        $query->find( __DIR__ . '/foobar' );
    }
}