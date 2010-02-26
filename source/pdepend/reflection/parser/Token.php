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
 * @package   pdepend\reflection\parser
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   SVN: $Id$
 * @link      http://pdepend.org/
 */

namespace pdepend\reflection\parser;

/**
 * Simple value object that represents a source token.
 *
 * @category  PHP
 * @package   pdepend\reflection\parser
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2009-2010 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class Token // @codeCoverageIgnoreStart
{
    // @codeCoverageIgnoreEnd

    /**
     * Real token offset within the token array returned by the php function
     * <b>token_get_all()</b>.
     *
     * @var integer
     */
    public $offset = 0;

    /**
     * The type or identifier of this token.
     *
     * @var integer
     */
    public $type = 0;

    /**
     * The textual token representation.
     *
     * @var string
     */
    public $image = null;

    /**
     * The start line number.
     *
     * @var integer
     */
    public $startLine = 0;

    /**
     * The end line number.
     *
     * @var integer
     */
    public $endLine = 0;

    /**
     * Constructs a new token instan ce.
     *
     * @param integer $offset    The original token offset.
     * @param integer $type      The internal token identifier.
     * @param string  $image     The textual representation of this token.
     * @param integer $startLine The token start line within the source file.
     * @param integer $endLine   The token end line within the source file.
     */
    public function __construct( $offset, $type, $image, $startLine, $endLine )
    {
        $this->offset    = $offset;
        $this->type      = $type;
        $this->image     = $image;
        $this->startLine = $startLine;
        $this->endLine   = $endLine;
    }

    // @codeCoverageIgnoreStart

    /**
     * Returns a string representation of this token.
     * 
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s[offset=%d;type=%d;image="%s"]',
            __CLASS__,
            $this->offset,
            $this->type,
            $this->image
        );
    }

    // @codeCoverageIgnoreEnd
}