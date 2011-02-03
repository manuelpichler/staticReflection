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

use pdepend\reflection\interfaces\ParserContext;

/**
 * Simple parser context implementation that will return a proxy instance for
 * each requested reflection class.
 *
 * @category  PHP
 * @package   pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionClassProxyContext implements ParserContext
{
    /**
     * The currently used reflection session.
     *
     * @var \pdepend\reflection\ReflectionSession
     */
    private $_session = null;

    /**
     * A simple cache class which holds already parsed and created reflection
     * class instances.
     *
     * @var \pdepend\reflection\factories\ReflectionClassCache
     */
    private $_classCache = null;

    /**
     * Constructs a new parser context instance.
     *
     * @param \pdepend\reflection\ReflectionSession $session The currently
     *        used reflection session instance which has created this parser
     *        context.
     */
    public function __construct( ReflectionSession $session )
    {
        $this->_session = $session;
        
        $this->_classCache = new ReflectionClassCache();
    }

    /**
     * This method will be called by the reflection parser for all found classes
     * and/or interfaces that the currently parsed class depends on.
     *
     * @param string $className Full qualified name of the request class.
     *
     * @return \ReflectionClass
     */
    public function getClassReference( $className )
    {
        if ( $this->_classCache->has( $className ) )
        {
            return $this->_classCache->restore( $className );
        }
        return new ReflectionClassProxy( $this, $className );
    }

    /**
     * Returns a previous registered <b>\ReflectionClass</b> instance that
     * matches the given class name. Or throws a reflection exception when no
     * matching class exists.
     *
     * @param string $className Full qualified name of the request class.
     *
     * @return \ReflectionClass
     * @throws \ReflectionException When no matching class or interface for the
     *         given name exists.
     */
    public function getClass( $className )
    {
        if ( $this->_classCache->has( $className ) )
        {
            return $this->_classCache->restore( $className );
        }
        return $this->_session->getClass( $className );
    }

    /**
     * This method can/should be called by the parser whenever the source of a
     * class or interface has been completed.
     *
     * @param \ReflectionClass $class A reflection class or interface instance
     *        that was processed by the parser.
     *
     * @return void
     */
    public function addClass( \ReflectionClass $class )
    {
        $this->_classCache->store( $class );
    }
}