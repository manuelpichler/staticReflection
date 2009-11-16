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
 * @package   org\pdepend\reflection\queries
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection\queries;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection class query.
 *
 * @category  PHP
 * @package   org\pdepend\reflection\queries
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionClassQueryTest extends \org\pdepend\reflection\BaseTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\queries\ReflectionClassQuery
     * @group reflection
     * @group reflection::queries
     * @group unittest
     */
    public function testFindByNameStopsProcessingWhenFactoryCanHandleTheClass()
    {
        $factory1 = $this->createFactory();
        $factory1->expects( $this->once() )
            ->method( 'hasClass' )
            ->with( $this->equalTo( 'QueryClass' ) )
            ->will( $this->returnValue( true ) );

        $factory2 = $this->createFactory();
        $factory2->expects( $this->never() )
            ->method( 'hasClass' );

        $query = new ReflectionClassQuery( array( $factory1, $factory2 ) );
        $query->findByName( 'QueryClass' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\queries\ReflectionClassQuery
     * @group reflection
     * @group reflection::queries
     * @group unittest
     */
    public function testFindByNameIteratesOverAllRegisteredFactories()
    {
        $factory1 = $this->createFactory();
        $factory1->expects( $this->once() )
            ->method( 'hasClass' )
            ->with( $this->equalTo( 'QueryClass' ) )
            ->will( $this->returnValue( false ) );

        $factory2 = $this->createFactory();
        $factory2->expects( $this->once() )
            ->method( 'hasClass' )
            ->with( $this->equalTo( 'QueryClass' ) )
            ->will( $this->returnValue( true ) );

        $query = new ReflectionClassQuery( array( $factory1, $factory2 ) );
        $query->findByName( 'QueryClass' );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\queries\ReflectionClassQuery
     * @group reflection
     * @group reflection::queries
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testFindByNameThrowsExceptionWhenNoMatchingClassExists()
    {
        $query = new ReflectionClassQuery( array() );
        $query->findByName( __CLASS__ );
    }
}