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

require_once __DIR__ . '/BaseTest.php';

/**
 * Test case for the reflection proxy parser context.
 *
 * @category  PHP
 * @package   pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionClassProxyContextTest extends BaseTest
{
    /**
     * testGetClassReturnsProxyReflectionClassInstance
     * 
     * @return void
     * @covers \pdepend\reflection\ReflectionClassProxyContext
     * @group reflection
     * @group unittest
     */
    public function testGetClassReturnsProxyReflectionClassInstance()
    {
        $context = new ReflectionClassProxyContext( $this->createSession() );
        self::assertInstanceOf( ReflectionClassProxy::TYPE, $context->getClassReference( 'Foo' ) );
    }

    /**
     * testGetClassReferenceReturnsClassInstanceOfPreviousRegisteredClass
     *
     * @return void
     * @covers \pdepend\reflection\ReflectionClassProxyContext
     * @group reflection
     * @group unittest
     */
    public function testGetClassReferenceReturnsClassInstanceOfPreviousRegisteredClass()
    {
        $class = new \ReflectionClass( __CLASS__ );
        
        $context = new ReflectionClassProxyContext( $this->createSession() );
        $context->addClass( $class );

        self::assertSame( $class, $context->getClassReference( __CLASS__ ) );
    }

    /**
     * testGetClassReferenceHandlesClassNamesCaseInsensitive
     *
     * @return void
     * @covers \pdepend\reflection\ReflectionClassProxyContext
     * @group reflection
     * @group unittest
     */
    public function testGetClassReferenceHandlesClassNamesCaseInsensitive()
    {
        $class = new \ReflectionClass( __CLASS__ );

        $context = new ReflectionClassProxyContext( $this->createSession() );
        $context->addClass( $class );

        self::assertSame( $class, $context->getClassReference( strtoupper( __CLASS__ ) ) );
    }

    /**
     * testGetClassReturnsClassInstanceOfPreviousRegisteredClass
     *
     * @return void
     * @covers \pdepend\reflection\ReflectionClassProxyContext
     * @group reflection
     * @group unittest
     */
    public function testGetClassReturnsClassInstanceOfPreviousRegisteredClass()
    {
        $class = new \ReflectionClass( __CLASS__ );

        $context = new ReflectionClassProxyContext( $this->createSession() );
        $context->addClass( $class );

        self::assertSame( $class, $context->getClass( __CLASS__ ) );
    }

    /**
     * testGetClassReferenceHandlesClassNamesCaseInsensitive
     *
     * @return void
     * @covers \pdepend\reflection\ReflectionClassProxyContext
     * @group reflection
     * @group unittest
     */
    public function testGetClassHandlesClassNamesCaseInsensitive()
    {
        $class = new \ReflectionClass( __CLASS__ );

        $session = $this->createSession();
        $session->expects( $this->never() )
            ->method( 'getClass' );

        $context = new ReflectionClassProxyContext( $session );
        $context->addClass( $class );

        $context->getClass( strtoupper( __CLASS__ ) );
    }

    /**
     * testGetClassDelegatesToSessionWhenMatchingClassExists
     *
     * @return void
     * @covers \pdepend\reflection\ReflectionClassProxyContext
     * @group reflection
     * @group unittest
     */
    public function testGetClassDelegatesToSessionWhenMatchingClassExists()
    {
        $session = $this->createSession();
        $session->expects( $this->once() )
            ->method( 'getClass' )
            ->with( $this->equalTo( __CLASS__ ) );

        $context = new ReflectionClassProxyContext( $session );
        $context->getClass( __CLASS__ );
    }
}