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

/**
 * Simple cache class that can be used to keep already found reflection class
 * instances in memory for faster access.
 *
 * @category  PHP
 * @package   pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2011 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class ReflectionClassCache
{
    /**
     * Cached reflection class instances.
     *
     * @var array(\ReflectionClass)
     */
    private $_classes = array();

    /**
     * This method checks if a class for the given <b>$className</b> already
     * exists in the cache.
     *
     * @param string $className Name of the searched class.
     *
     * @return boolean
     */
    public function has( $className )
    {
        return isset( $this->_classes[$this->_normalizeClassName( $className )] );
    }

    /**
     * This method will restore a previously created reflection class instance
     * for the given <b>$className</b>.
     *
     * @param string $className Name of the searched class.
     *
     * @return \ReflectionClass
     */
    public function restore( $className )
    {
        if ( $this->has( $className ) )
        {
            return $this->_classes[$this->_normalizeClassName( $className )];
        }
        throw new \LogicException( 'Class ' . $className . ' does not exist' );
    }

    /**
     * This method stores the given reflection class within the cache.
     *
     * @param \ReflectionClass $class The newly created reflection class.
     *
     * @return void
     */
    public function store( \ReflectionClass $class )
    {
        $this->_classes[$this->_normalizeClassName( $class->getName() )] = $class;
    }

    /**
     * Normalizes a class or interface name.
     *
     * @param string $className A class or interface name.
     *
     * @return string
     */
    private function _normalizeClassName( $className )
    {
        return ltrim( strtolower( $className ), '\\' );
    }
}