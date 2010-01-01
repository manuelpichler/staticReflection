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

require_once __DIR__ . '/BaseTest.php';

/**
 * Test case for the reflection session class.
 *
 * @category  PHP
 * @package   pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionSessionTest extends BaseTest
{
    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateDefaultSessionCreatesExpectedFactoryStack()
    {
        $resolver = $this->createResolver( 'SessionSimpleClass' );
        $session  = ReflectionSession::createDefaultSession( $resolver );

        $query = $session->createClassQuery();
        $class = $query->find( 'SessionSimpleClass' );

        $this->assertEquals( 'SessionSimpleClass', $class->getName() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateDefaultSessionCreatesExpectedFactoryStackWithInternalApiFirst()
    {
        $session = ReflectionSession::createDefaultSession( $this->createResolver() );

        $query = $session->createClassQuery();
        $class = $query->find( 'Iterator' );

        $this->assertTrue( $class->isInternal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateDefaultSessionCreatesExpectedFactoryStackWithNullFactory()
    {
        $session = ReflectionSession::createDefaultSession( $this->createResolver() );

        $query = $session->createClassQuery();
        $class = $query->find( __METHOD__ );

        $this->assertFalse( $class->isInternal() || $class->isUserDefined() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateStaticSessionCreatesTheExpectedFactoryStackWithStaticFactory()
    {
        $resolver = $this->createResolver( 'SessionSimpleClass' );
        $session  = ReflectionSession::createStaticSession( $resolver );

        $query = $session->createClassQuery();
        $class = $query->find( 'SessionSimpleClass' );

        $this->assertTrue( $class->isUserDefined() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateStaticSessionCreatesTheExpectedFactoryStackWithNullFactory()
    {
        $session = ReflectionSession::createStaticSession( $this->createResolver() );

        $query = $session->createClassQuery();
        $class = $query->find( __METHOD__ );

        $this->assertFalse( $class->isUserDefined() || $class->isInternal() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateInternalSessionCreatesExpectedExceptionStackWithInternalFactory()
    {
        $session = ReflectionSession::createInternalSession();

        $query = $session->createClassQuery();
        $class = $query->find( __CLASS__ );

        $this->assertType( '\ReflectionClass', $class );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testCreateInternalSessionStackForUnknownClass()
    {
        $session = ReflectionSession::createInternalSession();

        $query = $session->createClassQuery();
        $query->find( __METHOD__ );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testGetClassExecutesConfiguredReflectionClassFactory()
    {
        $factory = $this->getMock( '\pdepend\reflection\interfaces\ReflectionClassFactory' );
        $factory->expects( $this->once() )
            ->method( 'hasClass' )
            ->with( $this->equalTo( __CLASS__ ) )
            ->will( $this->returnValue( true ) );
        $factory->expects( $this->once() )
            ->method( 'createClass' )
            ->with( $this->equalTo( __CLASS__ ) )
            ->will( $this->returnValue( $this ) );

        $session = new ReflectionSession();
        $session->addClassFactory( $factory );

        $this->assertSame( $this, $session->getClass( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testGetClassExecutesConfiguredReflectionClassFactoriesInAddOrder()
    {
        $factory1 = $this->getMock( '\pdepend\reflection\interfaces\ReflectionClassFactory' );
        $factory1->expects( $this->once() )
            ->method( 'hasClass' )
            ->with( $this->equalTo( __CLASS__ ) )
            ->will( $this->returnValue( false ) );
        $factory1->expects( $this->never() )
            ->method( 'createClass' );

        $factory2 = $this->getMock( '\pdepend\reflection\interfaces\ReflectionClassFactory' );
        $factory2->expects( $this->once() )
            ->method( 'hasClass' )
            ->with( $this->equalTo( __CLASS__ ) )
            ->will( $this->returnValue( true ) );
        $factory2->expects( $this->once() )
            ->method( 'createClass' )
            ->with( $this->equalTo( __CLASS__ ) )
            ->will( $this->returnValue( $this ) );

        $session = new ReflectionSession();
        $session->addClassFactory( $factory1 );
        $session->addClassFactory( $factory2 );

        $this->assertSame( $this, $session->getClass( __CLASS__ ) );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateClassQueryReturnsAnObjectOfExpectedType()
    {
        $session = new ReflectionSession();
        $this->assertType( queries\ReflectionClassQuery::TYPE, $session->createClassQuery() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateFileQueryReturnsAnObjectOfExpectedType()
    {
        $session = new ReflectionSession();
        $this->assertType( queries\ReflectionFileQuery::TYPE, $session->createFileQuery() );
    }

    /**
     * @return void
     * @covers \pdepend\reflection\ReflectionSession
     * @group reflection
     * @group unittest
     */
    public function testCreateDirectoryQueryReturnsAnObjectOfExpectedType()
    {
        $session = new ReflectionSession();
        $this->assertType( queries\ReflectionDirectoryQuery::TYPE, $session->createDirectoryQuery() );
    }

    /**
     * Creates a mocked source resolver instance.
     *
     * @return \pdepend\reflection\interfaces\SourceResolver
     */
    protected function createResolver()
    {
        $resolver = parent::createResolver();

        if ( func_num_args() === 1 )
        {
            $resolver->expects( $this->once() )
                ->method( 'hasPathnameForClass' )
                ->with( $this->equalTo( func_get_arg( 0 ) ) )
                ->will( $this->returnValue( true ) );
        }

        return $resolver;
    }
}