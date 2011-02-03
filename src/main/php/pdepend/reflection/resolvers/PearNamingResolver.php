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
 * @package   pdepend\reflection\resolvers
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection\resolvers;

use pdepend\reflection\interfaces\SourceResolver;
use pdepend\reflection\exceptions\PathnameNotFoundException;

/**
 * This file resolver implementation uses the pear naming convention and the PHP
 * include path to find source file of a class or interface. Therefor it replaces
 * all underscores in the class with slashes and appends <i>.php</b> and tries
 * to find a matching class within the different include paths.
 *
 * <code>
 * set_include_path( getcwd() . PATH_SEPARATOR . get_include_path() );
 *
 * $resolver = new pdepend\reflection\resolvers\PearNamingResolver();
 * var_dump( $resolver->getPathnameForClass( 'PHP_Depend_Parser' );
 * </code>
 *
 * @category  PHP
 * @package   pdepend\reflection\resolvers
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class PearNamingResolver implements SourceResolver
{
    /**
     * Different paths extracted from the php include_path.
     *
     * @var array(string)
     */
    private $_paths = array();

    /**
     * Constructs a new pear style resolver instance.
     */
    public function __construct()
    {
        $paths = explode( PATH_SEPARATOR, get_include_path() );
        foreach ( $paths as $path )
        {
            $this->_paths[] = realpath( $path );
        }
    }

    /**
     * This method will return <b>true</b> when this resolver knows a source
     * file for a class with the given <b>$className</b>. Otherwise this method
     * will return <b>false</b>.
     *
     * @param string $className Name of the searched class that should.
     *
     * @return boolean
     */
    public function hasPathnameForClass( $className )
    {
        return count( $this->_createPossiblePathnamesForClass( $className ) ) > 0;
    }

    /**
     * Returns the file pathname where the given class is defined.
     *
     * @param string $className Name of the searched class that should.
     *
     * @return string
     * @throws \pdepend\reflection\exceptions\PathnameNotFoundException When
     *         not match can be found for the given class name.
     */
    public function getPathnameForClass( $className )
    {
        foreach ( $this->_createPossiblePathnamesForClass( $className ) as $path )
        {
            if ( is_string( $path ) )
            {
                return $path;
            }
        }
        throw new PathnameNotFoundException( $className );
    }

    /**
     * Creates an array with possible paths for the given class name.
     *
     * @param string $className Qualified name of the searched class.
     *
     * @return array(string)
     */
    private function _createPossiblePathnamesForClass( $className )
    {
        $pathnames = array();
        foreach ( $this->_paths as $path )
        {
            $pathnames[] = realpath( $path . '/' . strtr( $className, '_', '/' ) . '.php' );
        }
        return array_filter( $pathnames );
    }
}