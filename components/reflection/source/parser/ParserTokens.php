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

/**
 * The default parser tokens.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
final class ParserTokens
{
    /**
     * Supported parser tokens.
     */
    const T_ABSTRACT     = -1,
          T_ARRAY        = -2,
          T_AS           = -3,
          T_BITWISE_AND  = -4,
          T_BLOCK_CLOSE  = -5,
          T_BLOCK_OPEN   = -6,
          T_CLASS        = -7,
          T_CLASS_C      = -8,
          T_COMMA        = -9,
          T_CONST        = -10,
          T_DNUMBER      = -11,
          T_DOC_COMMENT  = -12,
          T_EQUAL        = -13,
          T_FALSE        = -14,
          T_FILE         = -15,
          T_FINAL        = -16,
          T_EXTENDS      = -17,
          T_FUNCTION     = -18,
          T_IMPLEMENTS   = -19,
          T_INTERFACE    = -20,
          T_LINE         = -21,
          T_LNUMBER      = -22,
          T_NAMESPACE    = -23,
          T_NS_SEPARATOR = -24,
          T_NULL         = -25,
          T_PARENT       = -26,
          T_PRIVATE      = -27,
          T_PROTECTED    = -28,
          T_PUBLIC       = -29,
          T_SELF         = -30,
          T_SEMICOLON    = -31,
          T_SCOPE_CLOSE  = -32,
          T_SCOPE_OPEN   = -33,
          T_STATIC       = -34,
          T_STRING       = -35,
          T_TEXT         = -36,
          T_TRUE         = -37,
          T_USE          = -38,
          T_VARIABLE     = -39;
}