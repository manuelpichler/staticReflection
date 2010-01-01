<?php
/**
 * This file is part of the static reflection component.
 *
 * PHP Version 5
 *
 * Copyright (c) 2009-2010, Manuel Pichler <mapi@pdepend.org>.
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
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection;

/**
 * Simple autoloader for the classes of the reflection component.
 *
 * @category  PHP
 * @package   pdepend\reflection
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class Autoloader
{
    /**
     * The source directory of the reflection component
     *
     * @var string
     */
    private $_pathname = null;

    /**
     * Constructs a new autoloader instance.
     *
     * @param string $pathname The source directory of the reflection component
     */
    public function __construct( $pathname = __DIR__ )
    {
        $this->_pathname = $pathname;
    }

    /**
     * Tries to load the source file for the given class.
     *
     * @param string $className Name of the searched class.
     *
     * @return void
     */
    public function autoload( $className )
    {
        if ( strpos( ltrim( $className, '\\' ), __NAMESPACE__ ) === 0 )
        {
            $this->_includeFileIfExists( $this->_createFileName( $className ) );
        }
    }

    /**
     * Will include the given file when it exists.
     *
     * @param string $filename The source file name.
     *
     * @return void
     */
    private function _includeFileIfExists( $filename )
    {
        if ( file_exists( $filename ) )
        {
            include $filename;
        }
    }

    /**
     * Creates the source file name for the given class.
     *
     * @param string $className Name of the searched class.
     *
     * @return string
     */
    private function _createFileName( $className )
    {
        $filename = substr( $className, strlen( __NAMESPACE__ ) + 1 );
        $filename = strtr( $filename, '\\', DIRECTORY_SEPARATOR );
        return $this->_pathname . DIRECTORY_SEPARATOR . $filename . '.php';
    }
}