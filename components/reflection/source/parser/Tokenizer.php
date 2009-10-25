<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\parser;

/**
 * Source tokenizer.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
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
        ','  =>  ParserTokens::T_COMMA,
        '}'  =>  ParserTokens::T_SCOPE_CLOSE,
        '{'  =>  ParserTokens::T_SCOPE_OPEN,
        ';'  =>  ParserTokens::T_SEMICOLON,
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
     * @return \org\pdepend\reflection\parser\Token
     */
    public function next()
    {
        $token = $this->peek();
        next( $this->_tokens );
        return $token;
    }

    /**
     * @return \org\pdepend\reflection\parser\Token
     */
    public function peek()
    {
        if ( is_object( $token = current( $this->_tokens ) ) )
        {
            return $token;
        }
        return self::EOF;
    }

    private function _tokenize( $source )
    {
        foreach ( token_get_all( $source ) as $offset => $token )
        {
            $this->_addToken( $offset, $token );
        }
    }

    private function _addToken( $offset, $token )
    {
        if ( ( $object = $this->_createToken( $offset, $token ) ) !== null )
        {
            $this->_tokens[] = $object;
        }
    }

    private function _createToken( $offset, $token )
    {
        if ( is_string( $token ) )
        {
            return $this->_createTokenFromString( $offset, $token );
        }
        return $this->_createTokenFromArray( $offset, $token );
    }

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

    private function _createTokenFromArray( $offset, array $token )
    {
        $startLine = $this->_startLine;
        $endLine   = $this->_updateStartLine( $token[1] );

        if ( isset( $this->_tokenTypeMap[$token[0]] ) )
        {
            return new Token( $offset, $this->_tokenTypeMap[$token[0]], $token[1], $startLine, $endLine );
        }
        return null;
    }

    private function _updateStartLine( $image )
    {
        return ( $this->_startLine += substr_count( $image, "\n" ) );
    }
}