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
 * Simple tokenizer implementation that utilizes PHP's tokenizer extension to
 * create a stream of {@link \org\pdepend\reflection\parser\Token} objects that
 * can be processed by a parser implementation.
 *
 * <code>
 * namespace org\pdepend\reflection\parser
 * {
 *     $source = '<?php class Foo { private $_bar = 42; }';
 *
 *     $tokenizer = new Tokenizer( $source );
 *     while ( $tokenizer->peek() !== Tokenizer::EOF )
 *     {
 *         $token = $tokenizer->next();
 *         printf(
 *             "Token(start=%d; end=%d; type=%d; image=%s)\n",
 *             $token->startLine,
 *             $token->endLine,
 *             $token->type,
 *             $token->image
 *         );
 *     }
 * }
 * </code>
 *
 * @category  PHP
 * @package   org\pdepend\reflection\parser
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/
 */
class Tokenizer
{
    /**
     * End of token stream identifier.
     */
    const EOF = -255;

    /**
     * Mapping between PHP's internal token ids and those used by this component.
     *
     * @var array(integer=>integer)
     */
    private $_tokenTypeMap = array(
        T_ABSTRACT                  =>  ParserTokens::T_ABSTRACT,
        T_AS                        =>  ParserTokens::T_AS,
        T_CLASS                     =>  ParserTokens::T_CLASS,
        T_CONST                     =>  ParserTokens::T_CONST,
        T_CURLY_OPEN                =>  ParserTokens::T_SCOPE_OPEN,
        T_DOC_COMMENT               =>  ParserTokens::T_DOC_COMMENT,
        T_DOLLAR_OPEN_CURLY_BRACES  =>  ParserTokens::T_SCOPE_OPEN,
        T_FINAL                     =>  ParserTokens::T_FINAL,
        T_EXTENDS                   =>  ParserTokens::T_EXTENDS,
        T_FUNCTION                  =>  ParserTokens::T_FUNCTION,
        T_IMPLEMENTS                =>  ParserTokens::T_IMPLEMENTS,
        T_INTERFACE                 =>  ParserTokens::T_INTERFACE,
        T_NAMESPACE                 =>  ParserTokens::T_NAMESPACE,
        T_NS_SEPARATOR              =>  ParserTokens::T_NS_SEPARATOR,
        T_PRIVATE                   =>  ParserTokens::T_PRIVATE,
        T_PROTECTED                 =>  ParserTokens::T_PROTECTED,
        T_PUBLIC                    =>  ParserTokens::T_PUBLIC,
        T_STATIC                    =>  ParserTokens::T_STATIC,
        T_STRING                    =>  ParserTokens::T_STRING,
        T_USE                       =>  ParserTokens::T_USE,
        T_VARIABLE                  =>  ParserTokens::T_VARIABLE,
    );

    /**
     * Mapping between string tokens and internal token identifiers.
     *
     * @var array(string=>integer)
     */
    private $_tokenCharMap = array(
        '&'  =>  ParserTokens::T_BITWISE_AND,
        ')'  =>  ParserTokens::T_BLOCK_CLOSE,
        '('  =>  ParserTokens::T_BLOCK_OPEN,
        ','  =>  ParserTokens::T_COMMA,
        '}'  =>  ParserTokens::T_SCOPE_CLOSE,
        '{'  =>  ParserTokens::T_SCOPE_OPEN,
        ';'  =>  ParserTokens::T_SEMICOLON,
    );

    /**
     * Mapping between PHP <b>T_STRING</b> token images and the internally used
     * token identifier.
     *
     * @var array(string=>integer)
     */
    private $_tokenWordMap = array(
        'parent'  =>  ParserTokens::T_PARENT,
        'self'    =>  ParserTokens::T_SELF,
    );

    /**
     * @var array(\org\pdepend\reflection\Token)
     */
    private $_tokens = array();

    /**
     * Start line of the current token.
     *
     * @var integer
     */
    private $_startLine = 1;

    /**
     * Constructs a new tokenizer instance.
     *
     * @param string $source The raw source code.
     */
    public function __construct( $source )
    {
        $this->_tokenize( $source );
    }

    /**
     * This method returns the next token from the token stream an moves the
     * internal stream pointer to the next token. When no more tokens are
     * available this method will return <b>EOF</b>.
     *
     * @return \org\pdepend\reflection\parser\Token|integer
     */
    public function next()
    {
        $token = $this->peek();
        next( $this->_tokens );
        return $token;
    }

    /**
     * This method returns the next available token from the token stream. When
     * no more tokens exist this method will return <b>EOF</b>.
     *
     * @return \org\pdepend\reflection\parser\Token|integer
     */
    public function peek()
    {
        if ( is_object( $token = current( $this->_tokens ) ) )
        {
            return $token;
        }
        return self::EOF;
    }

    /**
     * This method parses the given php source into a processable token stream.
     *
     * @param string $source The unparsed php source.
     *
     * @return void
     */
    private function _tokenize( $source )
    {
        foreach ( token_get_all( $source ) as $offset => $token )
        {
            $this->_addToken( $offset, $token );
        }
    }

    /**
     * This method will create a token instance and add it to the token stream.
     *
     * @param integer      $offset Token offset within the raw token stream.
     * @param string|array $token  Raw php token as returned by token_get_all().
     *
     * @return void
     */
    private function _addToken( $offset, $token )
    {
        if ( ( $object = $this->_createToken( $offset, $token ) ) !== null )
        {
            $this->_tokens[] = $object;
        }
    }

    /**
     * This method takes a raw php token as it is returned by the function
     * <b>token_get_all()</b> and translates it into an internally used token
     * object. When no transformation rule exists for the given token this
     * method will return <b>null</b>.
     *
     * @param integer      $offset Token offset within the raw token stream.
     * @param string|array $token  Raw php token as returned by token_get_all().
     *
     * @return \org\pdepend\reflection\parser\Token
     */
    private function _createToken( $offset, $token )
    {
        if ( is_string( $token ) )
        {
            return $this->_createTokenFromString( $offset, $token );
        }
        return $this->_createTokenFromArray( $offset, $token );
    }

    /**
     * This method creates a token instance from a raw strong token, when it is
     * listed in the token translation map and return that object. In all other
     * cases this method will simply return <b>null</b>.
     *
     * @param integer $offset Token offset within the raw token stream.
     * @param string  $token  A raw php token string.
     *
     * @return \org\pdepend\reflection\parser\Token
     */
    private function _createTokenFromString( $offset, $token )
    {
        $startLine = $this->_startLine;
        $endLine   = $this->_updateStartLine( $token );

        if ( isset( $this->_tokenCharMap[$token] ) )
        {
            return new Token( $offset, $this->_tokenCharMap[$token], $token, $startLine, $endLine );
        }
        return null;
    }

    /**
     * This method creates token instance from the given raw php token, when its
     * type is listed in the token type whitelist. Otherwise this method will
     * simply return <b>null</b>.
     *
     * @param integer $offset Token offset within the raw token stream.
     * @param array   $token  A raw php token array.
     *
     * @return \org\pdepend\reflection\parser\Token
     */
    private function _createTokenFromArray( $offset, array $token )
    {
        $startLine = $this->_startLine;
        $endLine   = $this->_updateStartLine( $token[1] );

        if ( $token[0] === T_STRING && isset( $this->_tokenWordMap[strtolower( $token[1] )] ) )
        {
            $type = $this->_tokenWordMap[strtolower( $token[1] )];
        }
        else if ( isset( $this->_tokenTypeMap[$token[0]] ) )
        {
            $type = $this->_tokenTypeMap[$token[0]];
        }
        else
        {
            return null;
        }
        return new Token( $offset, $type, $token[1], $startLine, $endLine );
    }

    /**
     * This method updates the start line of the next token based on the number
     * of newlines within the given source image. The return value contains the
     * start line of the next token.
     *
     * @param string $image The source image of a token.
     *
     * @return integer
     */
    private function _updateStartLine( $image )
    {
        return ( $this->_startLine += substr_count( $image, "\n" ) );
    }
}