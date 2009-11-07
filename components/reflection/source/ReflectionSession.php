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

namespace org\pdepend\reflection;

use org\pdepend\reflection\interfaces\ParserContext;
use org\pdepend\reflection\interfaces\SourceResolver;
use org\pdepend\reflection\interfaces\ReflectionClassFactory;

class ReflectionSession
{
    /**
     * The source file resolver that will be used by the static reflection
     * implementation to retrieve the source file name for a given class name.
     *
     * @var \org\pdepend\reflection\interfaces\SourceResolver
     */
    private $_resolver = null;

    /**
     * The configured class factory stack. The session will ask each factory
     * for a given name in the order they were added.
     *
     * @var array(org\pdepend\reflection\interfaces\ReflectionClassFactory)
     */
    private $_classFactories = array();

    private $_running = false;

    /**
     * Creates the default reflection session implementation. This setup tries
     * to provide an optimal combination between static and internal reflection
     * implementations.
     *
     * When ever possible this setup uses PHP's internal reflection api, when
     * it is not possible to retrieve a class with this implementation the
     * static implementation will be used to create a reflection class instance.
     * 
     * This solution should provide the best mix between speed and flexibility
     * without poluting the class scope with uneccessary class definitions.
     *
     * @param \org\pdepend\reflection\interfaces\SourceResolver $resolver The
     *        source file resolver that will be used by the static reflection
     *        implementation to retrieve the source file name for a given class
     *        name.
     *
     * @return \org\pdepend\reflection\ReflectionSession
     * @todo Implement and document the null termination
     */
    public static function createDefaultSession( SourceResolver $resolver )
    {
        $session = new ReflectionSession();
        $session->setResolver( $resolver );
        $session->addClassFactory( new factories\InternalReflectionClassFactory() );
        $session->addClassFactory( new factories\StaticReflectionClassFactory( new ProxyParserContext( $session ), $resolver ) );

        return $session;
    }

    /**
     * Sets the source resolver that will be used to find a source code file for
     * a given class name.
     *
     * @param \org\pdepend\reflection\interfaces\SourceResolver $resolver The
     *        source file resolver that will be used by the static reflection
     *        implementation to retrieve the source file name for a given class
     *        name.
     *
     * @return void
     */
    protected function setResolver( SourceResolver $resolver )
    {
        $this->_resolver = $resolver;
    }

    public function addClassFactory( ReflectionClassFactory $classFactory )
    {
        $this->_classFactories[] = $classFactory;
    }

    public function createFileQuery()
    {
        return new queries\ReflectionFileQuery( new ProxyParserContext( $this ) );
    }

    public function createDirectoryQuery()
    {
        return new queries\ReflectionDirectoryQuery( new ProxyParserContext( $this ) );
    }

    /**
     *
     * @param string $className
     *
     * @return \ReflectionClass
     */
    public function getClass( $className )
    {
        if ( $this->_running )
        {
            return new ReflectionClassProxy( $this, $className );
        }

        $this->_running = true;
        $class = $this->_getClass( $className );
        $this->_running = false;

        return $class;
    }

    /**
     *
     * @param string $className
     *
     * @return \ReflectionClass
     */
    private function _getClass( $className )
    {
        foreach ( $this->_classFactories as $factory )
        {
            if ( $factory->hasClass( $className ) )
            {
                return $factory->createClass( $className );
            }
        }
        throw new \ReflectionException( 'Class ' . $className . ' does not exist' );
    }
}

class ProxyParserContext implements ParserContext
{
    private $_session = null;

    public function __construct( ReflectionSession $session )
    {
        $this->_session = $session;
    }

    /**
     *
     * @param string $className
     *
     * @return \ReflectionClass
     */
    public function getClass( $className )
    {
        return new ReflectionClassProxy( $this->_session, $className );
    }
}