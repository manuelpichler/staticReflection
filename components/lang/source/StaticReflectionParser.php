<?php

namespace de\buzz2ee\lang;

class StaticReflectionParser
{
    /**
     * @var string
     */
    private $_fileName = null;

    /**
     * @var \de\buzz2ee\lang\StaticReflectionTokenizer
     */
    private $_tokenizer = null;

    /**
     * @var string
     */
    private $_namespace = '';

    /**
     * @var array(string=>string)
     */
    private $_aliasMap = array();

    /**
     * @param string $fileName
     */
    public function __construct( $fileName )
    {
        $this->_fileName = $fileName;
    }

    /**
     * @return \de\buzz2ee\lang\interfaces\ReflectionClass
     */
    public function parse()
    {
        $this->_tokenizer = new StaticReflectionTokenizer( file_get_contents( $this->_fileName ) );

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_NAMESPACE:
                    $this->_parseNamespace();
                    break;

                case ParserTokens::T_USE:
                    $this->_parseUseStatements();
                    break;
                
                case ParserTokens::T_DOC_COMMENT;
                    break;

                case ParserTokens::T_ABSTRACT:
                    break;

                case ParserTokens::T_FINAL:
                    break;

                case ParserTokens::T_CLASS:
                    $class = $this->_parseClassDeclaration();
                    var_dump( $class );
                    break;

                case ParserTokens::T_INTERFACE:
                    $class = $this->_parseInterfaceDeclaration();
                    var_dump( $class );
                    break;
            }

            echo $token, PHP_EOL;
        }
    }

    /**
     *
     * @return \de\
     */
    private function _parseNamespace()
    {
        $this->_namespace = '';
        $this->_aliasMap  = array();

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_DOC_COMMENT;
                    break;
                
                case ParserTokens::T_STRING:
                case ParserTokens::T_NS_SEPARATOR:
                    $this->_namespace .= $token->image;
                    break;

                case ParserTokens::T_SEMICOLON:
                    return $token;
            }
        }
        throw new \RuntimeException( 'Invalid token in namespace declaration found.' );
    }

    private function _parseUseStatements()
    {
        do
        {
            $token = $this->_parseUseStatement();
        }
        while ( $token->type === ParserTokens::T_COMMA );
    }

    private function _parseUseStatement()
    {
        $namespace = '';
        $alias     = '';

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_DOC_COMMENT;
                    break;

                case ParserTokens::T_STRING:
                    $alias = $token->image;
                    
                case ParserTokens::T_NS_SEPARATOR:
                    $namespace .= $token->image;
                    break;

                case ParserTokens::T_AS:
                    $alias = $this->_tokenizer->next();
                    break;

                case ParserTokens::T_COMMA:
                case ParserTokens::T_SEMICOLON:
                    $this->_aliasMap[$alias] = ltrim( $namespace, '\\' ) . '\\';

                    return $token;
            }
        }
        throw new \RuntimeException( 'Invalid token in use statement found.' );
    }

    private function _parseClassDeclaration()
    {
        $class = null;

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_DOC_COMMENT;
                    break;

                case ParserTokens::T_STRING:
                    $class = new StaticReflectionClass( $this->_namespace . $token->image );
                    break;

                case ParserTokens::T_EXTENDS:
                    $class->setParentClass( new StaticReflectionClass( $this->_parseClassName() ) );
                    break;

                case ParserTokens::T_IMPLEMENTS:
                    $this->_parseClassNames();
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    return $class;

                default:
                    throw new \RuntimeException( sprintf( 'Unexpected token("%") in class declaration.', $token->image ) );
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in class declaration.' );
    }

    private function _parseInterfaceDeclaration()
    {
        $class = null;

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_DOC_COMMENT;
                    break;

                case ParserTokens::T_STRING:
                    $class = new StaticReflectionInterface( $this->_namespace . $token->image );
                    break;

                case ParserTokens::T_EXTENDS:
                    foreach ( $this->_parseClassNames() as $className )
                    {

                    }
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    return $class;

                default:
                    throw new \RuntimeException( sprintf( 'Unexpected token("%") in interface declaration.', $token->image ) );
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in interface declaration.' );
    }

    private function _parseClassNames()
    {
        $classNames = array();

        while ( is_object( $token = $this->_peek() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_COMMA:
                case ParserTokens::T_DOC_COMMENT;
                    $this->_next();
                    break;

                case ParserTokens::T_STRING:
                case ParserTokens::T_NS_SEPARATOR:
                    $classNames[] = $this->_parseClassName();
                    break;

                case ParserTokens::T_IMPLEMENTS:
                case ParserTokens::T_SCOPE_OPEN:
                    return $classNames;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in class name list.' );
    }

    private function _parseClassName()
    {
        $className = array();

        while ( is_object( $token = $this->_peek() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_DOC_COMMENT;
                    $this->_next();
                    break;

                case ParserTokens::T_STRING:
                case ParserTokens::T_NS_SEPARATOR:
                    $this->_next();
                    
                    $className[] = $token->image;
                    break;

                default:
                    if ( isset( $this->_aliasMap[$className[0]] ) )
                    {
                        $className[0] = $this->_aliasMap[$className[0]];
                    }
                    else if ( $className[0] === '\\' )
                    {
                        array_shift( $className );
                    }
                    else
                    {
                        array_unshift( $className, $this->_namespace );
                    }
                    return $className;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in class name.' );
    }

    /**
     * @return \de\buzz2ee\lang\Token
     */
    private function _peek()
    {
        if ( is_object( $token = $this->_tokenizer->peek() ) )
        {
            return $token;
        }
        return null;
    }

    /**
     * @return \de\buzz2ee\lang\Token
     */
    private function _next()
    {
        if ( is_object( $token = $this->_tokenizer->next() ) )
        {
            return $token;
        }
        return null;
    }
}

interface ReflectionClass
{
    function getName();

    function isInterface();

    function getInterfaces();

    function getParentClass();
}

class StaticReflectionInterface implements ReflectionClass
{
    private $_name = null;

    private $_interfaces = array();

    public function __construct( $name )
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function isInterface()
    {
        return true;
    }

    public function getInterfaces()
    {
        return $this->_interfaces;
    }

    public function addInterface( ReflectionClass $interface )
    {
        $this->_interfaces[] = $interface;
    }

    public function getParentClass()
    {
        return null;
    }
}

class StaticReflectionClass extends StaticReflectionInterface
{
    private $_parentClass = null;

    public function isInterface()
    {
        return false;
    }

    public function getParentClass()
    {
        return $this->_parentClass;
    }

    public function setParentClass( ReflectionClass $parentClass )
    {
        $this->_parentClass = $parentClass;
    }
}

class ParserTokens
{
    const T_ABSTRACT     = -1,
          T_AS           = -2,
          T_CLASS        = -3,
          T_COMMA        = -4,
          T_DOC_COMMENT  = -5,
          T_FINAL        = -6,
          T_EXTENDS      = -7,
          T_FUNCTION     = -8,
          T_IMPLEMENTS   = -9,
          T_INTERFACE    = -10,
          T_NAMESPACE    = -11,
          T_NS_SEPARATOR = -12,
          T_PRIVATE      = -13,
          T_PROTECTED    = -14,
          T_PUBLIC       = -15,
          T_SEMICOLON    = -16,
          T_SCOPE_CLOSE  = -17,
          T_SCOPE_OPEN   = -18,
          T_STRING       = -19,
          T_USE          = -20;
}

class StaticReflectionTokenizer
{
    const EOF = -255;

    private $_tokenTypeMap = array(
        T_ABSTRACT                  =>  ParserTokens::T_ABSTRACT,
        T_AS                        =>  ParserTokens::T_AS,
        T_CLASS                     =>  ParserTokens::T_CLASS,
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
        T_STRING                    =>  ParserTokens::T_STRING,
        T_USE                       =>  ParserTokens::T_USE
    );

    private $_tokenCharMap = array(
        ','  =>  ParserTokens::T_COMMA,
        '}'  =>  ParserTokens::T_SCOPE_CLOSE,
        '{'  =>  ParserTokens::T_SCOPE_OPEN,
        ';'  =>  ParserTokens::T_SEMICOLON,
    );

    private $_tokens = array();

    public function __construct( $source )
    {
        $this->_tokenize( $source );
    }

    /**
     * @return \de\buzz2ee\lang\Token
     */
    public function next()
    {
        $token = $this->peek();
        next( $this->_tokens );
        return $token;
    }

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
        $this->_tokens = array();
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
        if ( isset( $this->_tokenCharMap[$token] ) )
        {
            return new Token( $offset, $this->_tokenCharMap[$token], $token );
        }
        return null;
    }

    private function _createTokenFromArray( $offset, array $token )
    {

        if ( isset( $this->_tokenTypeMap[$token[0]] ) )
        {
            return new Token( $offset, $this->_tokenTypeMap[$token[0]], $token[1] );
        }
        return null;
    }
}

class Token
{
    public $offset = 0;
    public $type   = 0;
    public $image  = null;

    public function __construct( $offset, $type, $image )
    {
        $this->offset = $offset;
        $this->type   = $type;
        $this->image  = $image;
    }

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
}