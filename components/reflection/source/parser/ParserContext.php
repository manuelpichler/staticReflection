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
 * @package   org\pdepend\reflection\parser
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection\parser;

use \org\pdepend\reflection\ReflectionSession;
use \org\pdepend\reflection\interfaces\SourceResolver;

class ParserContext
{
    /**
     * The currently used reflection session instance.
     *
     * @var \org\pdepend\reflection\ReflectionSession
     */
    private $_session = null;

    /**
     * The configured source resolver.
     *
     * @var \org\pdepend\reflection\interfaces\SourceResolver
     */
    private $_resolver = null;

    /**
     * Constructs a new parser context.
     *
     * @param ReflectionSession $session  The currently used reflection session.
     * @param SourceResolver    $resolver The static source resolver.
     */
    public function __construct( ReflectionSession $session, SourceResolver $resolver )
    {
        $this->_session  = $session;
        $this->_resolver = $resolver;
    }

    /**
     * Returns a reflection instance for the given class/interface name.
     *
     * @param string $className Name of the searched class/interface.
     *
     * @return \ReflectionClass
     */
    public function getClass( $className )
    {
        return $this->_session->getClass( $className );
    }

    /**
     * Returns the source code for the given class/interface.
     *
     * @param string $className Name of the currently searched class/interface.
     *
     * @return string
     */
    public function getSource( $className )
    {
        return $this->_resolver->getSourceForClass( $className );
    }

    /**
     * Returns the pathname of source file for the given class/interface.
     *
     * @param string $className Name of the currently searched class/interface.
     *
     * @return string
     */
    public function getPathname( $className )
    {
        return $this->_resolver->getPathnameForClass( $className );
    }
}