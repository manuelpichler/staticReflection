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
 * @package   pdepend\reflection\resolvers
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection\resolvers;

require_once __DIR__ . '/../BaseTest.php';

/**
 * Test cases for the parser class.
 *
 * @category  PHP
 * @package   pdepend\reflection\resolvers
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class PearNamingResolverTest extends \pdepend\reflection\BaseTest
{
    /**
     * The temporary include path fixture.
     *
     * @var string
     */
    private $_includePathFixture = '';

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_includePathFixture = realpath( __DIR__ . '/../_source' );

        set_include_path( $this->_includePathFixture );
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        restore_include_path();

        parent::tearDown();
    }

    /**
     * @return void
     * @covers \pdepend\reflection\interfaces\SourceResolver
     * @covers \pdepend\reflection\resolvers\PearNamingResolver
     * @group reflection
     * @group reflection::resolvers
     * @group unittest
     */
    public function testHasPathnameForClassReturnsTrueForExistingFile()
    {
        $resolver = new PearNamingResolver();
        $exists   = $resolver->hasPathnameForClass( 'resolver_pear_ResolverPearSimpleClass' );

        self::assertTrue( $exists );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\interfaces\SourceResolver
     * @covers \pdepend\reflection\resolvers\PearNamingResolver
     * @group reflection
     * @group reflection::resolvers
     * @group unittest
     */
    public function testHasPathnameForClassReturnsFalseForMissingFile()
    {
        $resolver = new PearNamingResolver();
        $exists   = $resolver->hasPathnameForClass( 'resolver_pear_ResolverPearMissingClass' );

        self::assertFalse( $exists );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\interfaces\SourceResolver
     * @covers \pdepend\reflection\resolvers\PearNamingResolver
     * @group reflection
     * @group reflection::resolvers
     * @group unittest
     */
    public function testGetPathnameForClassReturnsExpectedPathname()
    {
        $expected = realpath( $this->_includePathFixture . '/resolver/pear/ResolverPearSimpleClass.php' );

        $resolver = new PearNamingResolver();
        $pathname = $resolver->getPathnameForClass( 'resolver_pear_ResolverPearSimpleClass' );

        self::assertEquals( $expected, $pathname );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\interfaces\SourceResolver
     * @covers \pdepend\reflection\resolvers\PearNamingResolver
     * @group reflection
     * @group reflection::resolvers
     * @group unittest
     * @expectedException \pdepend\reflection\exceptions\PathnameNotFoundException
     */
    public function testGetPathnameForClassThrowsExceptionWhenNoMatchExistsForTheGivenClassName()
    {
        $resolver = new PearNamingResolver();
        $resolver->getPathnameForClass( 'resolver_pear_ResolverPearMissingClass' );
    }
}