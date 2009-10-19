<?php

namespace de\buzz2ee\reflection;

use de\buzz2ee\reflection\interfaces\Reflector;
use de\buzz2ee\reflection\interfaces\ParserTokens;

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

        $modifiers  = 0;
        $docComment = '';

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
                    $modifiers |= Reflector::IS_ABSTRACT;
                    break;

                case ParserTokens::T_FINAL:
                    $modifiers |= Reflector::IS_FINAL;
                    break;

                case ParserTokens::T_CLASS:
                    $class = $this->_parseClassDeclaration( $docComment, $modifiers );
                    var_dump( $class );
                    $modifiers  = 0;
                    $docComment = '';
                    break;

                case ParserTokens::T_INTERFACE:
                    $class = $this->_parseInterfaceDeclaration( $docComment );
                    
                    var_dump( $class );
                    $modifiers  = 0;
                    $docComment = '';
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
                case ParserTokens::T_DOC_COMMENT;
                    break;

                case ParserTokens::T_STRING:
                    $name  = $this->_namespace . $token->image;
                    $class = new StaticReflectionClass( $name, $docComment, $modifiers );
                    break;

                case ParserTokens::T_EXTENDS:
                    $class->setParentClass( $this->_parseParentClass() );
                    break;

                case ParserTokens::T_IMPLEMENTS:
                    $class->setInterfaces( $this->_parseInterfaceList() );
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    list( $methods, $properties ) = $this->_parseClassOrInterfaceScope();

                    $class->setMethod( $methods );
                    $class->setProperties( $properties );
                    return $class;

                default:
                    throw new \RuntimeException( sprintf( 'Unexpected token("%") in class declaration.', $token->image ) );
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
                case ParserTokens::T_DOC_COMMENT;
                    break;

                case ParserTokens::T_STRING:
                    $name  = $this->_namespace . $token->image;
                    $class = new StaticReflectionInterface( $name, $docComment );
                    break;

                case ParserTokens::T_EXTENDS:
                    $class->setInterfaces( $this->_parseInterfaceList() );
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    list( $methods ) = $this->_parseClassOrInterfaceScope( Reflector::IS_ABSTRACT );

                    $class->setMethod( $methods );
                    return $class;

                default:
                    throw new \RuntimeException( sprintf( 'Unexpected token("%") in interface declaration.', $token->image ) );
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in interface declaration.' );
    }

    /**
     * @return \de\buzz2ee\lang\StaticReflectionClass
     */
    private function _parseParentClass()
    {
        return new StaticReflectionClass( $this->_parseClassOrInterfaceName() );
    }

    /**
     * @return array(\de\buzz2ee\lang\StaticReflectionInterface)
     */
    private function _parseInterfaceList()
    {
        $interfaces = array();

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
                    $interfaces[] = $this->_parseInterface();
                    break;

                case ParserTokens::T_IMPLEMENTS:
                case ParserTokens::T_SCOPE_OPEN:
                    return $interfaces;
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in class name list.' );
    }

    /**
     * @return \de\buzz2ee\lang\StaticReflectionInterface
     */
    private function _parseInterface()
    {
        return new StaticReflectionInterface( $this->_parseClassOrInterfaceName() );
    }

    /**
     * @return string
     */
    private function _parseClassOrInterfaceName()
    {
        $classNameParts = array();

        while ( is_object( $token = $this->_peek() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_DOC_COMMENT;
                    $this->_next();
                    break;

                case ParserTokens::T_STRING:
                case ParserTokens::T_NS_SEPARATOR:
                    $classNameParts[] = $this->_next()->image;
                    break;

                default:
                    return $this->_createClassOrInterfaceName( $classNameParts );
            }
        }
        throw new \RuntimeException( 'Unexpected end of token stream in class name.' );
    }

    private function _parseClassOrInterfaceScope( $defaultModifiers = 0 )
    {
        $methods    = array();
        $properties = array();

        $scope      = 1;
        $modifiers  = $defaultModifiers | Reflector::IS_PUBLIC;
        $docComment = '';

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_DOC_COMMENT:
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    ++$scope;
                    break;

                case ParserTokens::T_SCOPE_CLOSE:
                    --$scope;
                    break;

                case ParserTokens::T_ABSTRACT:
                    $modifiers |= Reflector::IS_ABSTRACT;
                    break;

                case ParserTokens::T_FINAL:
                    $modifiers |= Reflector::IS_FINAL;
                    break;

                case ParserTokens::T_PUBLIC:
                    $modifiers |= Reflector::IS_PUBLIC;
                    break;

                case ParserTokens::T_PRIVATE:
                    $modifiers ^= Reflector::IS_PUBLIC;
                    $modifiers |= Reflector::IS_PRIVATE;
                    break;

                case ParserTokens::T_PROTECTED:
                    $modifiers ^= Reflector::IS_PUBLIC;
                    $modifiers |= Reflector::IS_PROTECTED;
                    break;

                case ParserTokens::T_STATIC:
                    $modifiers |= Reflector::IS_STATIC;
                    break;

                case ParserTokens::T_FUNCTION:
                    $methods[]  = $this->_parseMethodDeclaration( $docComment, $modifiers );
                    $modifiers  = $defaultModifiers | Reflector::IS_PUBLIC;
                    $docComment = '';
                    break;

                case ParserTokens::T_VARIABLE:
                    $properties[] = $this->_parsePropertyDeclaration( $token->image, $docComment, $modifiers );
                    $modifiers    = $defaultModifiers | Reflector::IS_PUBLIC;
                    $docComment   = '';
                    break;
            }

            if ( $scope === 0 )
            {
                return array( $methods, $properties );
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

    private function _parsePropertyDeclaration( $propertyName, $docComment, $modifiers )
    {
        return new StaticReflectionProperty( substr( $propertyName, 1 ), $docComment, $modifiers );
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
            array_unshift( $parts, $this->_namespace );
        }
        return rtrim( join( '', $parts ), '\\' );
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