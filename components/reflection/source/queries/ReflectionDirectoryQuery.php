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

/**
 * This query class allows access to reflection class instances for all classes
 * and interfaces declared in a given directory.
 *
 * <code>
 * $query   = $session->createDirectoryQuery();
 * $classes = $query->findByDirectory( __DIR__ . '/source' );
 *
 * foreach ( $classes as $class )
 * {
 *     echo 'Class: ', $class->getShortName(), PHP_EOL,
 *          'File:  ', $class->getFileName(), PHP_EOL,
 *          '-- ', PHP_EOL;
 * }
 * </code>
 *
 * @category  PHP
 * @package   org\pdepend\reflection\queries
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionDirectoryQuery extends ReflectionQuery
{
    /**
     * This method will create reflection class instances for all interfaces
     * and classes that can be found in the source code files within the given
     * directory.
     *
     * @param string $directory The source directory that is the target of the
     *        class search.
     *
     * @return array(\ReflectionClass)
     */
    public function findByDirectory( $directory )
    {
        if ( file_exists( $directory ) === false || is_file( $directory ) )
        {
            throw new \LogicException( 'Invalid or not existant directory ' . $directory );
        }

        $classes = array();
        foreach ( $this->_createIterator( $directory ) as $fileInfo )
        {
            if ( $fileInfo->isFile() === false )
            {
                continue;
            }
            foreach ( $this->parseFile( $fileInfo->getRealpath() ) as $class )
            {
                $classes[] = $class;
            }
        }
        return $classes;
    }

    /**
     * This method returns a iterator with all files that could be found within
     * the given source directory.
     *
     * @param string $directory The source directory that is the target of the
     *        class search.
     *
     * @return Iterator
     */
    private function _createIterator( $directory )
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator( $directory )
        );
    }
}