<?php

namespace de\buzz2ee\reflection\parser;

use de\buzz2ee\reflection\interfaces\SourceResolver;

use de\buzz2ee\reflection\StaticReflectionClass;
use de\buzz2ee\reflection\StaticReflectionMethod;
use de\buzz2ee\reflection\StaticReflectionInterface;
use de\buzz2ee\reflection\StaticReflectionProperty;

class Parser
{
    /**
     * @var \de\buzz2ee\reflection\interfaces\SourceResolver
     */
    private $_resolver = null;

    /**
     * @var string
     */
    private $_className = null;

    /**
     * @var \de\buzz2ee\lang\Tokenizer
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
     * @param \de\buzz2ee\reflection\interfaces\SourceResolver $resolver
     * @param string                                           $className
     */
    public function __construct( SourceResolver $resolver, $className )
    {
        $this->_resolver  = $resolver;
        $this->_className = trim( $className, '\\' );
    }

    /**
     * @return \de\buzz2ee\lang\interfaces\ReflectionClass
     */
    public function parse()
    {
        $this->_tokenizer = new Tokenizer( $this->_resolver->getSourceForClass( $this->_className ) );

        $modifiers  = 0;
        $docComment = '';

        $class = null;
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
                    $docComment = $token->image;
                    break;

                case ParserTokens::T_ABSTRACT:
                    $modifiers |= StaticReflectionClass::IS_EXPLICIT_ABSTRACT;
                    break;

                case ParserTokens::T_FINAL:
                    $modifiers |= StaticReflectionClass::IS_FINAL;
                    break;

                case ParserTokens::T_CLASS:
                    $class = $this->_parseClassDeclaration( $docComment, $modifiers );
                    $class->initStartLine( $token->startLine );

                    $modifiers  = 0;
                    $docComment = '';
                    break;

                case ParserTokens::T_INTERFACE:
                    $class = $this->_parseInterfaceDeclaration( $docComment );
                    $class->initStartLine( $token->startLine );
                    
                    $modifiers  = 0;
                    $docComment = '';
                    break;
            }

            if ( $class !== null && $class->getName() === $this->_className )
            {
                $class->initFileName( $this->_resolver->getPathnameForClass( $this->_className ) );
                return $class;
            }
        }
        throw new \LogicException( 'No class ' . $this->_className . ' found.' );
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
                case ParserTokens::T_DOC_COMMENT:
                    break;

                case ParserTokens::T_STRING:
                case ParserTokens::T_NS_SEPARATOR:
                    $this->_namespace .= $token->image;
                    break;

                case ParserTokens::T_SEMICOLON:
                case ParserTokens::T_SCOPE_OPEN:
                    return $token;

                default:
                    throw new \RuntimeException( 'Invalid token in namespace declaration found.' );
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream.' );
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
                case ParserTokens::T_STRING:
                    $alias = $token->image;
                    
                case ParserTokens::T_NS_SEPARATOR:
                    $namespace .= $token->image;
                    break;

                case ParserTokens::T_AS:
                    $alias = $this->_tokenizer->next()->image;
                    break;

                case ParserTokens::T_COMMA:
                case ParserTokens::T_SEMICOLON:
                    $this->_aliasMap[$alias] = trim( $namespace, '\\' );

                    return $token;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in use statement.' );
    }

    /**
     * @return \de\buzz2ee\lang\StaticReflectionClass
     */
    private function _parseClassDeclaration( $docComment, $modifiers )
    {
        $class = null;

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_STRING:
                    $name  = $this->_createClassOrInterfaceName( array( $token->image ) );
                    $class = new StaticReflectionClass( $name, $docComment, $modifiers );
                    break;

                case ParserTokens::T_EXTENDS:
                    $class->setParentClass( $this->_parseParentClass() );
                    break;

                case ParserTokens::T_IMPLEMENTS:
                    $class->initInterfaces( $this->_parseInterfaceList() );
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    list( $methods, $properties, $endLine ) = $this->_parseClassOrInterfaceScope();

                    $class->initEndLine( $endLine );
                    $class->initMethods( $methods );
                    $class->setProperties( $properties );
                    return $class;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in class declaration.' );
    }

    /**
     * @return \de\buzz2ee\lang\StaticReflectionInterface
     */
    private function _parseInterfaceDeclaration( $docComment )
    {
        $class = null;

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_STRING:
                    $name  = $this->_createClassOrInterfaceName( array( $token->image ) );
                    $class = new StaticReflectionInterface( $name, $docComment );
                    break;

                case ParserTokens::T_EXTENDS:
                    $class->initInterfaces( $this->_parseInterfaceList() );
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    list( $methods, $properties, $endLine ) = $this->_parseClassOrInterfaceScope( StaticReflectionMethod::IS_ABSTRACT );

                    $class->initEndLine( $endLine );
                    $class->initMethods( $methods );
                    return $class;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in interface declaration.' );
    }

    /**
     * @return \de\buzz2ee\lang\StaticReflectionClass
     */
    private function _parseParentClass()
    {
        $parser = new static( $this->_resolver, $this->_parseClassOrInterfaceName() );
        return $parser->parse();
    }

    /**
     * @return array(\de\buzz2ee\lang\StaticReflectionInterface)
     */
    private function _parseInterfaceList()
    {
        $interfaces = array();
        
        while ( ( $tokenType = $this->_peek() ) !== Tokenizer::EOF )
        {
            switch ( $tokenType )
            {
                case ParserTokens::T_STRING:
                case ParserTokens::T_NS_SEPARATOR:
                    $interfaces[] = $this->_parseInterface();
                    break;

                case ParserTokens::T_IMPLEMENTS:
                case ParserTokens::T_SCOPE_OPEN:
                    return $interfaces;

                default:
                    $this->_next();
                    break;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in class name list.' );
    }

    /**
     * @return \de\buzz2ee\lang\StaticReflectionInterface
     */
    private function _parseInterface()
    {
        $parser = new static( $this->_resolver, $this->_parseClassOrInterfaceName() );
        return $parser->parse();
    }

    /**
     * @return string
     */
    private function _parseClassOrInterfaceName()
    {
        $name = array();

        $this->_consumeComments();
        if ( $this->_peek() === ParserTokens::T_NS_SEPARATOR )
        {
            $name[] = $this->_next()->image;
        }

        while ( $this->_peek() !== Tokenizer::EOF )
        {
            $this->_consumeComments();
            $token = $this->_consumeToken( ParserTokens::T_STRING );

            $name[] = $token->image;

            $this->_consumeComments();
            if ( $this->_peek() === ParserTokens::T_NS_SEPARATOR )
            {
                $name[] = $this->_next()->image;
            }
            else
            {
                break;
            }
        }
        return $this->_createClassOrInterfaceName( $name );
    }

    private function _parseClassOrInterfaceScope( $defaultModifiers = 0 )
    {
        $methods    = array();
        $properties = array();

        $modifiers  = $defaultModifiers | StaticReflectionMethod::IS_PUBLIC;
        $docComment = '';

        while ( ( $tokenType = $this->_peek() ) !== Tokenizer::EOF )
        {
            switch ( $tokenType )
            {
                case ParserTokens::T_DOC_COMMENT:
                    $token      = $this->_consumeToken( ParserTokens::T_DOC_COMMENT );
                    $docComment = $token->image;
                    break;

                case ParserTokens::T_SCOPE_CLOSE:
                    $token = $this->_consumeToken( ParserTokens::T_SCOPE_CLOSE );

                    return array( $methods, $properties, $token->endLine);

                case ParserTokens::T_ABSTRACT:
                    $this->_consumeToken( ParserTokens::T_ABSTRACT );
                    $modifiers |= StaticReflectionMethod::IS_ABSTRACT;
                    break;

                case ParserTokens::T_FINAL:
                    $this->_consumeToken( ParserTokens::T_FINAL );
                    $modifiers |= StaticReflectionMethod::IS_FINAL;
                    break;

                case ParserTokens::T_PUBLIC:
                    $this->_consumeToken( ParserTokens::T_PUBLIC );
                    $modifiers |= StaticReflectionMethod::IS_PUBLIC;
                    break;

                case ParserTokens::T_PRIVATE:
                    $this->_consumeToken( ParserTokens::T_PRIVATE );
                    $modifiers ^= StaticReflectionMethod::IS_PUBLIC;
                    $modifiers |= StaticReflectionMethod::IS_PRIVATE;
                    break;

                case ParserTokens::T_PROTECTED:
                    $this->_consumeToken( ParserTokens::T_PROTECTED );
                    $modifiers ^= StaticReflectionMethod::IS_PUBLIC;
                    $modifiers |= StaticReflectionMethod::IS_PROTECTED;
                    break;

                case ParserTokens::T_STATIC:
                    $this->_consumeToken( ParserTokens::T_STATIC );
                    $modifiers |= StaticReflectionMethod::IS_STATIC;
                    break;

                case ParserTokens::T_FUNCTION:
                    $this->_consumeToken( ParserTokens::T_FUNCTION );
                    $methods[]  = $this->_parseMethodDeclaration( $docComment, $modifiers );
                    $modifiers  = $defaultModifiers | StaticReflectionMethod::IS_PUBLIC;
                    $docComment = '';
                    break;

                case ParserTokens::T_VARIABLE:
                    $properties = array_merge(
                        $properties,
                        $this->_parsePropertyDeclarations( $docComment, $modifiers )
                    );

                    $modifiers  = $defaultModifiers | StaticReflectionMethod::IS_PUBLIC;
                    $docComment = '';
                    break;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in class or interface body.' );
    }

    private function _parseMethodDeclaration( $docComment, $modifiers )
    {
        $methodName = null;

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_STRING:
                    if ( $methodName === null )
                    {
                        $methodName = $token->image;
                    }
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    $this->_parseScope();

                case ParserTokens::T_SEMICOLON:
                    return new StaticReflectionMethod( $methodName, $docComment, $modifiers );
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in method declaration.' );
    }

    private function _parsePropertyDeclarations( $docComment, $modifiers )
    {
        $this->_consumeComments();
        $properties = array( $this->_parsePropertyDeclaration( $docComment, $modifiers ) );

        $this->_consumeComments();
        while ( ( $tokensType = $this->_peek() ) === ParserTokens::T_COMMA )
        {
            $this->_consumeToken( ParserTokens::T_COMMA );

            $docComment   = $this->_consumeComments();
            $properties[] = $this->_parsePropertyDeclaration( $docComment, $modifiers );
            
            $this->_consumeComments();
        }
        $this->_consumeToken( ParserTokens::T_SEMICOLON );

        return $properties;
    }

    private function _parsePropertyDeclaration( $docComment, $modifiers )
    {
        $this->_consumeComments();
        $token = $this->_consumeToken( ParserTokens::T_VARIABLE );

        while( ( $tokenType = $this->_peek() ) !== Tokenizer::EOF )
        {
            switch ( $tokenType )
            {
                case ParserTokens::T_DOC_COMMENT:
                case ParserTokens::T_STATIC:
                case ParserTokens::T_STRING:
                    $this->_consumeToken( $tokenType );
                    break;

                case ParserTokens::T_COMMA:
                case ParserTokens::T_SEMICOLON:
                    break 2;
            }
        }
        return new StaticReflectionProperty( substr( $token->image, 1 ), $docComment, $modifiers );
    }

    private function _parseScope()
    {
        $scope = 1;

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_SCOPE_OPEN:
                    ++$scope;
                    break;

                case ParserTokens::T_SCOPE_CLOSE:
                    --$scope;
                    break;
            }

            if ( $scope === 0 )
            {
                return;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in method declaration.' );
    }

    /**
     * @param array(string) $parts
     *
     * @return string
     */
    private function _createClassOrInterfaceName( array $parts )
    {
        if ( isset( $this->_aliasMap[$parts[0]] ) )
        {
            $parts[0] = $this->_aliasMap[$parts[0]];
        }
        else if ( $parts[0] === '\\' )
        {
            array_shift( $parts );
        }
        else
        {
            array_unshift( $parts, $this->_namespace, '\\' );
        }
        return trim( join( '', $parts ), '\\' );
    }

    /**
     * @param integer $tokenType
     *
     * @return \de\buzz2ee\reflection\parser\Token
     */
    private function _consumeToken( $tokenType )
    {
        if ( is_object( $token = $this->_next() ) === false )
        {
            throw new \RuntimeException( 'Unexpected end of token stream.' );
        }
        if ( $token->type !==  $tokenType )
        {
            throw new \RuntimeException( sprintf( 'Unexpected token(%s) found.', $token->image ) );
        }
        return $token;
    }

    private function _consumeComments()
    {
        $comment = '';
        while ( $this->_peek() === ParserTokens::T_DOC_COMMENT )
        {
            $comment = $this->_next()->image;
        }
        return $comment;
    }

    /**
     * @return \de\buzz2ee\reflection\parser\Token
     */
    private function _peek()
    {
        if ( is_object( $token = $this->_tokenizer->peek() ) )
        {
            return $token->type;
        }
        return $token;
    }

    /**
     * @return \de\buzz2ee\reflection\parser\Token
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