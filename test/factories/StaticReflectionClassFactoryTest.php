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
 * @package   pdepend\reflection\factories
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection\factories;

require_once 'BaseTest.php';

/**
 * Test cases for the static reflection factory.
 *
 * @category  PHP
 * @package   pdepend\reflection\factories
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class StaticReflectionClassFactoryTest extends \pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\factories\StaticReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testHasClassReturnsFalseForClassThatDoesNotExist()
    {
        $resolver = $this->createResolver();
        $resolver->expects( $this->once() )
            ->method( 'hasPathnameForClass' )
            ->with( $this->equalTo( __CLASS__ ) )
            ->will( $this->returnValue( false ) );

        $factory = new StaticReflectionClassFactory( $this->createContext(), $resolver );
        $this->assertFalse( $factory->hasClass( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\factories\StaticReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testHasClassReturnsTrueForClassThatExists()
    {
        $resolver = $this->createResolver();
        $resolver->expects( $this->once() )
            ->method( 'hasPathnameForClass' )
            ->with( $this->equalTo( __CLASS__ ) )
            ->will( $this->returnValue( true ) );

        $factory = new StaticReflectionClassFactory( $this->createContext(), $resolver );
        $this->assertTrue( $factory->hasClass( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\factories\StaticReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testHasClassReturnsTrueWhenClassForTheGivenNameWasAlreadyParsed()
    {
        $factory = new StaticReflectionClassFactory( $this->createContext(), $this->createResolver() );
        $factory->createClass( 'ClassOneInFile' );

        $this->assertTrue( $factory->hasClass( 'ClassTwoInFile' ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\factories\StaticReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testCreateClassReturnsReflectionClassForClassThatExists()
    {
        $factory = new StaticReflectionClassFactory( $this->createContext(), $this->createResolver() );
        $class   = $factory->createClass( '\pdepend\reflection\ClassSimple' );

        $this->assertType( '\ReflectionClass', $class );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\factories\StaticReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     */
    public function testCreateClassReturnsReflectionClassForPreviousParsedFile()
    {
        $factory = new StaticReflectionClassFactory( $this->createContext(), $this->createResolver() );
        $factory->createClass( 'ClassOneInFile' );

        $class = $factory->createClass( 'ClassTwoInFile' );
        $this->assertType( '\ReflectionClass', $class );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\factories\StaticReflectionClassFactory
     * @group reflection
     * @group reflection::factories
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testCreateClassThrowsExceptionForClassThatDoesNotExists()
    {
        $factory = new StaticReflectionClassFactory( $this->createContext(), $this->createResolver() );
        $factory->createClass( '\pdepend\reflection\ClassMissing' );
    }
}