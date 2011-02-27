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
 * @package   pdepend\reflection\regression
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection\regression;

require_once __DIR__ . '/../BaseTest.php';

/**
 * Test case for ticket #10498195
 *
 * @category  PHP
 * @package   pdepend\reflection\regression
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      https://www.pivotaltracker.com/story/show/10498195
 *
 * @ticket 10498195
 * @covers \stdClass
 * @group reflection
 * @group reflection::regression
 * @group regressiontest
 */
class Bug010498195Test extends \pdepend\reflection\BaseTest
{
    /**
     * testParserAcceptsParentAsClassName
     *
     * @return void
     */
    public function testParserAcceptsParentAsClassName()
    {
        $class = $this->getClassByName( 'bug_010498195\Parent' );
        self::assertEquals( 'bug_010498195\Parent', $class->getName() );
    }

    /**
     * testParserAcceptsNullAsClassName
     *
     * @return void
     */
    public function testParserAcceptsNullAsClassName()
    {
        $class = $this->getClassByName( 'bug_010498195\Null' );
        self::assertEquals( 'bug_010498195\Null', $class->getName() );
    }

    /**
     * testParserAcceptsTrueAsClassName
     *
     * @return void
     */
    public function testParserAcceptsTrueAsClassName()
    {
        $class = $this->getClassByName( 'bug_010498195\True' );
        self::assertEquals( 'bug_010498195\True', $class->getName() );
    }

    /**
     * testParserAcceptsFalseAsClassName
     *
     * @return void
     */
    public function testParserAcceptsFalseAsClassName()
    {
        $class = $this->getClassByName( 'bug_010498195\False' );
        self::assertEquals( 'bug_010498195\False', $class->getName() );
    }

    /**
     * testParserAcceptsParentAsParentClassName
     *
     * @return void
     */
    public function testParserAcceptsParentAsParentClassName()
    {
        $class = $this->getClassByName( 'bug_010498195\ExtendsParent' );
        self::assertEquals( 'bug_010498195\Parent', $class->getParentClass()->getName() );
    }

    /**
     * testParserAcceptsFalseAsClassName
     *
     * @return void
     */
    public function testParserAcceptsSelfAsParentClassName()
    {
        $class = $this->getClassByName( 'bug_010498195\ExtendsSelf' );
        self::assertEquals( 'Self', $class->getParentClass()->getShortName() );
    }
}