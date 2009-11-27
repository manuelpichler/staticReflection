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

use org\pdepend\reflection\interfaces\SourceResolver;
use org\pdepend\reflection\interfaces\ReflectionClassFactory;

/**
 * Primary facade of the reflection component.
 *
 * @category  PHP
 * @package   org\pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionSession
{
    /**
     * The configured class factory stack. The session will ask each factory
     * for a given name in the order they were added.
     *
     * @var array(org\pdepend\reflection\interfaces\ReflectionClassFactory)
     */
    private $_classFactories = array();

    /**
     * Creates the default reflection session implementation. This setup tries
     * to provide an optimal combination between static and internal reflection
     * implementations.
     *
     * When ever possible this setup uses PHP's internal reflection api, when
     * it is not possible to retrieve a class with this implementation the
     * static implementation will be used to create a reflection class instance.
     * Finally this version of the reflection session will fallback to a null
     * implementation for requested classes that act like a placeholder for the
     * missing class/interface.
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
     */
    public static function createDefaultSession( SourceResolver $resolver )
    {
        $session = new ReflectionSession();
        $session->addClassFactory( new factories\InternalReflectionClassFactory() );
        $session->addClassFactory( new factories\StaticReflectionClassFactory( new ReflectionClassProxyContext( $session ), $resolver ) );
        $session->addClassFactory( new factories\NullReflectionClassFactory() );

        return $session;
    }

    /**
     * This factory method creates a pure static reflection session that parses
     * the source code to find classes/interfaces that should be reflection. It
     * does not rely on PHP's internal reflection api.
     *
     * This session configuration uses to different strategies to generate a
     * reflection class instance. First it asks the the supplied resource for a
     * correspond source file and when it exists it will parse that source file.
     * In all other cases this setup has a fallback to null reflection classes
     * that act as a placeholder for the missing source fragments.
     *
     * @param \org\pdepend\reflection\interfaces\SourceResolver $resolver The
     *        source file resolver that will be used by the static reflection
     *        implementation to retrieve the source file name for a given class
     *        name.
     *
     * @return \org\pdepend\reflection\ReflectionSession
     */
    public static function createStaticSession( SourceResolver $resolver )
    {
        $session = new ReflectionSession();
        $session->addClassFactory( new factories\StaticReflectionClassFactory( new ReflectionClassProxyContext( $session ), $resolver ) );
        $session->addClassFactory( new factories\NullReflectionClassFactory() );

        return $session;
    }

    /**
     * This factory method creates a reflection session instance that is a simple
     * wrapper around PHP's internal reflection api. It does not rely on any
     * parsing or suggestions and can only create reflection class instances for
     * existing classes and/or interfaces.
     *
     * @return \org\pdepend\reflection\ReflectionSession
     */
    public static function createInternalSession()
    {
        $session = new ReflectionSession();
        $session->addClassFactory( new factories\InternalReflectionClassFactory() );

        return $session;
    }

    /**
     * This method can be used to configure a custom process stack for the
     * reflection session. You can add various reflection factories that will
     * be asked in their add order if they can create a reflection class.
     *
     * @param \org\pdepend\reflection\interfaces\ReflectionClassFactory $classFactory A
     *        class factory that can be used as source for a reflection class
     *        instance.
     *
     * @return void
     */
    public function addClassFactory( ReflectionClassFactory $classFactory )
    {
        $this->_classFactories[] = $classFactory;
    }

    /**
     * Shortcut method that allows direct access to a class or interface by its
     * full qualified name.
     *
     * <code>
     * $class = $session->getClass( '\org\pdepend\reflection\ReflectionSession' );
     *
     * echo 'Class:     ', $class->getShortName(), PHP_EOL,
     *      'Namespace: ', $class->getNamespaceName(), PHP_EOL,
     *      'File:      ', $class->getFileName(), PHP_EOL;
     * </code>
     *
     * @param string $className The full qualified name of the search class.
     *
     * @return \ReflectionClass
     * @throws \ReflectionException When no matching class or interface for the
     *         given name exists.
     */
    public function getClass( $className )
    {
        return $this->createClassQuery()->findByName( $className );
    }

    /**
     * This method creates class query instance which allows direct access to
     * classes or interfaces by their name.
     *
     * @return \org\pdepend\reflection\queries\ReflectionClassQuery
     */
    public function createClassQuery()
    {
        return new queries\ReflectionClassQuery( $this->_classFactories );
    }

    /**
     * This method creates file query instance which allows access to all
     * classes and interfaces declared within a given file.
     *
     * @return \org\pdepend\reflection\queries\ReflectionFileQuery
     */
    public function createFileQuery()
    {
        return new queries\ReflectionFileQuery( new ReflectionClassProxyContext( $this ) );
    }

    /**
     * This method creates directory query instance which allows access to all
     * classes and interfaces declared in all files that can be found in a given
     * directory.
     *
     * @return \org\pdepend\reflection\queries\ReflectionDirectoryQuery
     */
    public function createDirectoryQuery()
    {
        return new queries\ReflectionDirectoryQuery( new ReflectionClassProxyContext( $this ) );
    }
}