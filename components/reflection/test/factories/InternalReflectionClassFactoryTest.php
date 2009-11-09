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
 * @package   org\pdepend\reflection\factories
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection\factories;

require_once 'BaseTest.php';

/**
 * Test cases for the interface reflection factory.
 *
 * @category  PHP
 * @package   org\pdepend\reflection\factories
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class InternalReflectionClassFactoryTest extends \org\pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\factories\InternalReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testHasClassReturnsFalseForClassThatDoesNotExist()
    {
        $factory = new InternalReflectionClassFactory();
        $this->assertFalse( $factory->hasClass( '__' . __CLASS__ . '__' ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\factories\InternalReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testHasClassReturnsTrueForClassThatExists()
    {
        $factory = new InternalReflectionClassFactory();
        $this->assertTrue( $factory->hasClass( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\factories\InternalReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testHasClassReturnsTrueForInterfaceThatExists()
    {
        $factory = new InternalReflectionClassFactory();
        $this->assertTrue( $factory->hasClass( '\Iterator' ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\factories\InternalReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testCreateClassReturnsReflectionClassForClassThatExists()
    {
        $factory = new InternalReflectionClassFactory();
        $class   = $factory->createClass( __CLASS__ );

        $this->assertType( '\ReflectionClass', $class );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\factories\InternalReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testCreateClassThrowsExceptionForClassThatDoesNotExists()
    {
        $factory = new InternalReflectionClassFactory();
        $factory->createClass( '__' . __CLASS__ . '__' );
    }
}