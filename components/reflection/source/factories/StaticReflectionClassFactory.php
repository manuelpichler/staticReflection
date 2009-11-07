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
 * @package   org\pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection\factories;

use org\pdepend\reflection\parser\Parser;
use org\pdepend\reflection\ReflectionSession;
use org\pdepend\reflection\ReflectionClassProxy;
use org\pdepend\reflection\interfaces\ReflectionFactory;
use org\pdepend\reflection\interfaces\SourceResolver;

class StaticReflectionClassFactory implements ReflectionFactory
{
    /**
     *
     * @var \org\pdepend\reflection\ReflectionSession
     */
    private $_session = null;

    /**
     *
     * @var \org\pdepend\reflection\interfaces\SourceResolver
     */
    private $_resolver = null;

    private $_registry = array();

    private $_parsing = false;

    public function __construct( ReflectionSession $session, SourceResolver $resolver )
    {
        $this->_session  = $session;
        $this->_resolver = $resolver;
    }

    public function hasClass( $className )
    {
        return true;
    }

    public function createClass( $className )
    {
        return $this->_createOrReturnClass( $className );
    }

    private function _createOrReturnClass( $className )
    {
        if ( $this->_parsing )
        {
            return new ReflectionClassProxy( $this->_session, $className );
        }
        
        $normalizedName = $this->_normalizeName( $className );
        if ( !isset( $this->_registry[$normalizedName] ) )
        {
            $this->_createClass( $className );
        }
        if ( isset( $this->_registry[$normalizedName] ) )
        {
            return $this->_registry[$normalizedName];
        }
        throw new \ReflectionException( 'Class ' . $className . ' does not exist' );
    }

    private function _createClass( $className )
    {
        $this->_parsing = true;

        $parser  = new Parser( $this );
        $classes = $parser->parseFile( $this->_resolver->getPathnameForClass( $className ) );
        foreach ( $classes as $class )
        {
            $this->_registry[$this->_normalizeName( $class->getName() )] = $class;
        }

        $this->_parsing = false;
        
        return $this->_registry;
    }

    /**
     * Normalizes a class or interface name.
     *
     * @param string $name A class or interface name.
     *
     * @return string
     */
    private function _normalizeName( $name )
    {
        return ltrim( strtolower( $name ), '\\' );
    }
}