<?php

namespace de\buzz2ee\reflection\parser;

use de\buzz2ee\reflection\interfaces\SourceResolver;

use de\buzz2ee\reflection\StaticReflectionClass;
use de\buzz2ee\reflection\StaticReflectionMethod;
use de\buzz2ee\reflection\StaticReflectionInterface;
use de\buzz2ee\reflection\StaticReflectionParameter;
use de\buzz2ee\reflection\StaticReflectionProperty;
use de\buzz2ee\reflection\exceptions\EndOfTokenStreamException;
use de\buzz2ee\reflection\exceptions\UnexpectedTokenException;

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
     * Parsed methods within a class or interface scope.
     *
     * @var array(\de\buzz2ee\reflection\StaticReflectionMethod)
     */
    private $_methods = array();

    /**
     * Parsed properties within a class scope.
     *
     * @var array(\de\buzz2ee\reflection\StaticReflectionMethod)
     */
    private $_properties = array();

    /**
     * Parsed constants within a class or interface scope.
     *
     * @var array(string=>mixed)
     */
    private $_constants = array();

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
                    throw new UnexpectedTokenException(
                        $token,
                        $this->_resolver->getPathnameForClass( $this->_className )
                    );
            }
        }
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
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
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
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
                    $class->initParentClass( $this->_parseParentClass() );
                    break;

                case ParserTokens::T_IMPLEMENTS:
                    $class->initInterfaces( $this->_parseInterfaceList() );
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    $endLine = $this->_parseClassOrInterfaceScope();

                    $class->initEndLine( $endLine );
                    $class->initMethods( $this->_methods );
                    $class->initConstants( $this->_constants );
                    $class->initProperties( $this->_properties );
                    return $class;
            }
        }
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
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
                    $endLine = $this->_parseClassOrInterfaceScope( StaticReflectionMethod::IS_ABSTRACT );

                    $class->initEndLine( $endLine );
                    $class->initMethods( $this->_methods );
                    $class->initConstants( $this->_constants );
                    return $class;
            }
        }
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
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
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
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
        $this->_methods    = array();
        $this->_constants  = array();
        $this->_properties = array();

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

                    return $token->endLine;

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

                case ParserTokens::T_CONST:
                    $this->_parseConstantDeclarations();
                    break;

                case ParserTokens::T_FUNCTION:
                    $this->_parseMethodDeclaration( $docComment, $modifiers );

                    $modifiers  = $defaultModifiers | StaticReflectionMethod::IS_PUBLIC;
                    $docComment = '';
                    break;

                case ParserTokens::T_VARIABLE:
                    $this->_parsePropertyDeclarations( $docComment, $modifiers );

                    $modifiers  = $defaultModifiers | StaticReflectionMethod::IS_PUBLIC;
                    $docComment = '';
                    break;

                default:
                    throw new UnexpectedTokenException(
                        $this->_next(),
                        $this->_resolver->getPathnameForClass( $this->_className )
                    );
            }
        }
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
    }

    /**
     * Parses a method declaration for/from the currently parsed class/interface.
     *
     * @param string  $docComment Optional doc comment for the parsed method.
     * @param integer $modifiers  Bitfield with method modifiers.
     *
     * @return \de\buzz2ee\reflection\StaticReflectionClass
     */
    private function _parseMethodDeclaration( $docComment, $modifiers )
    {
        $startLine  = $this->_consumeToken( ParserTokens::T_FUNCTION )->startLine;
        $methodName = null;
        $parameters = array();

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

                case ParserTokens::T_VARIABLE:
                    $parameters[] = new StaticReflectionParameter( $token->image, count( $parameters ) );
                     break;

                case ParserTokens::T_SCOPE_OPEN:
                    $token = $this->_parseScope();

                case ParserTokens::T_SEMICOLON:
                    $method = new StaticReflectionMethod( $methodName, $docComment, $modifiers );
                    $method->initStartLine( $startLine );
                    $method->initEndLine( $token->endLine );
                    $method->initParameters( $parameters );

                    $this->_methods[] = $method;

                    return;
            }
        }
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
    }

    private function _parsePropertyDeclarations( $docComment, $modifiers )
    {
        $this->_consumeComments();
        $this->_parsePropertyDeclaration( $docComment, $modifiers );

        $this->_consumeComments();
        while ( ( $tokensType = $this->_peek() ) === ParserTokens::T_COMMA )
        {
            $this->_consumeToken( ParserTokens::T_COMMA );

            $docComment = $this->_consumeComments();
            $this->_parsePropertyDeclaration( $docComment, $modifiers );
            
            $this->_consumeComments();
        }
        $this->_consumeToken( ParserTokens::T_SEMICOLON );
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
        $this->_properties[] = new StaticReflectionProperty( $token->image, $docComment, $modifiers );
    }

    private function _parseConstantDeclarations()
    {
        $this->_consumeToken( ParserTokens::T_CONST );
        $this->_parseConstantDeclaration();

        $this->_consumeComments();
        while ( ($tokenType = $this->_peek() ) === ParserTokens::T_COMMA )
        {
            $this->_consumeToken( ParserTokens::T_COMMA );
            $this->_parseConstantDeclaration();
            $this->_consumeComments();
        }
        $this->_consumeToken( ParserTokens::T_SEMICOLON );
    }

    private function _parseConstantDeclaration()
    {
        $this->_consumeComments();
        $token = $this->_consumeToken( ParserTokens::T_STRING );

        $this->_parseStaticScalar();
        $this->_constants[$token->image] = null;
    }

    private function _parseStaticScalar()
    {
        $this->_consumeComments();
        while( ( $tokenType = $this->_peek() ) !== Tokenizer::EOF )
        {
            switch ( $tokenType )
            {
                case ParserTokens::T_DOC_COMMENT:
                case ParserTokens::T_NS_SEPARATOR:
                case ParserTokens::T_STATIC:
                case ParserTokens::T_STRING:
                    $this->_next();
                    break;

                case ParserTokens::T_COMMA:
                case ParserTokens::T_SEMICOLON:
                    return;

                default:
                    throw new UnexpectedTokenException(
                        $this->_next(),
                        $this->_resolver->getPathnameForClass( $this->_className )
                    );
            }
            $this->_consumeComments();
        }
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
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
                return $token;
            }
        }
        throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
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
            throw new EndOfTokenStreamException( $this->_resolver->getPathnameForClass( $this->_className ) );
        }
        if ( $token->type !==  $tokenType )
        {
            throw new UnexpectedTokenException(
                $token,
                $this->_resolver->getPathnameForClass( $this->_className )
            );
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