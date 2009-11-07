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
 * @package   org\pdepend\reflection\resolvers
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace org\pdepend\reflection\resolvers;

use org\pdepend\reflection\interfaces\SourceResolver;
use org\pdepend\reflection\exceptions\PathnameNotFoundException;

/**
 * This file resolver implementation uses arrays like they are used for
 * autoloading to resolve the source file for a given class name.
 *
 * <code>
 * $array = array(
 *     '\org\pdepend\reflection\api\Parser'       => '/home/mapi/projects/...',
 *     '\org\pdepend\reflection\api\ParserTokens' => '/home/mapi/projects/...',
 *     // ...
 * );
 *
 * $resolver = new org\pdepend\reflection\resolvers\AutoloadArrayResolver( $array );
 * var_dump( $resolver->getPathnameForClass( '\org\pdepend\reflection\api\Parser' );
 * </code>
 *
 * @category  PHP
 * @package   org\pdepend\reflection\resolvers
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class AutoloadArrayResolver implements SourceResolver
{
    /**
     * Mapping between class names the corresponding source file.
     *
     * @var array(string=>string)
     */
    private $_autoload = array();
    
    /**
     * Constructs a new autoload array resolver instance.
     *
     * @param array(string=>string) $autoload Class to file mapping.
     */
    public function __construct( array $autoload = array() )
    {
        $this->addAutoloadArray( $autoload );
    }

    /**
     * Adds an array with class to file associations to this resolver instance.
     *
     * @param array(string=>string) $autoload Class to file mapping.
     *
     * @return void
     */
    public function addAutoloadArray( array $autoload )
    {
        foreach ( $autoload as $className => $fileName )
        {
            $this->_autoload[$this->_normalizeName( $className )] = $fileName;
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
        return isset( $this->_autoload[$this->_normalizeName( $className )] );
    }

    /**
     * Returns the file pathname where the given class is defined.
     *
     * @param string $className
     *
     * @return string
     * @throws \org\pdepend\reflection\exceptions\PathnameNotFoundException When
     *         not match can be found for the given class name.
     */
    public function getPathnameForClass( $className )
    {
        if ( $this->hasPathnameForClass( $className ) )
        {
            return $this->_autoload[$this->_normalizeName( $className )];
        }
        throw new PathnameNotFoundException( $className );
    }

    /**
     * Normalizes the given class name so that we can provide case insensitive
     * lookups.
     *
     * @param string $className The raw input class name.
     *
     * @return string
     */
    private function _normalizeName( $className )
    {
        return strtolower( ltrim( $className, '\\' ) );
    }
}