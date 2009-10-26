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

use org\pdepend\reflection\interfaces\SourceResolver;

use org\pdepend\reflection\api\StaticReflectionClass;
use org\pdepend\reflection\api\StaticReflectionMethod;
use org\pdepend\reflection\api\StaticReflectionInterface;
use org\pdepend\reflection\api\StaticReflectionParameter;
use org\pdepend\reflection\api\StaticReflectionProperty;
use org\pdepend\reflection\exceptions\EndOfTokenStreamException;
use org\pdepend\reflection\exceptions\UnexpectedTokenException;

/**
 * Parser implementation based on PHP's internal tokenizer extension.
 * 
 * @category  PHP
 * @package   org\pdepend\reflection\parser
 * @author    Manuel Pichler <mapi@pdepend.org>
 * @copyright 2008-2009 Manuel Pichler. All rights reserved.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: @package_version@
 * @link      http://pdepend.org/

 */
class Parser
{
    /**
     * Configured parser context that will be used to retrieve source information
     * and dependent class or interfaces.
     *
     * @var \org\pdepend\reflection\parser\ParserContext
     */
    private $_context = null;

    /**
     * Name of the searched class.
     *
     * @var string
     */
    private $_className = null;

    /**
     * The used source tokenizer.
     *
     * @var \org\pdepend\reflection\parser\Tokenizer
     */
    private $_tokenizer = null;

    /**
     * The currently parsed namespace.
     *
     * @var string
     */
    private $_namespace = '';

    /**
     * Alias map created from <b>use</b> statements.
     *
     * @var array(string=>string)
     */
    private $_aliasMap = array();

    /**
     * Parsed methods within a class or interface scope.
     *
     * @var array(\org\pdepend\reflection\api\StaticReflectionMethod)
     */
    private $_methods = array();

    /**
     * Parsed properties within a class scope.
     *
     * @var array(\org\pdepend\reflection\api\StaticReflectionMethod)
     */
    private $_properties = array();

    /**
     * Parsed constants within a class or interface scope.
     *
     * @var array(string=>mixed)
     */
    private $_constants = array();

    /**
     * Constructs a new parser instance.
     *
     * @param \org\pdepend\reflection\parser\ParserContext $context
     * @param string                                       $className
     */
    public function __construct( ParserContext $context, $className )
    {
        $this->_context   = $context;
        $this->_className = trim( $className, '\\' );
    }

    /**
     * @return \org\pdepend\reflection\api\StaticReflectionInterface
     */
    public function parse()
    {
        $this->_tokenizer = new Tokenizer( $this->_context->getSource( $this->_className ) );

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
                $class->initFileName( $this->_context->getPathname( $this->_className ) );
                return $class;
            }
        }
        throw new \ReflectionException( 'Class ' . $this->_className . ' does not exist' );
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
                        $this->_context->getPathname( $this->_className )
                    );
            }
        }
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
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
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
    }

    /**
     * @return \org\pdepend\reflection\api\StaticReflectionClass
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
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
    }

    /**
     * @return \org\pdepend\reflection\api\StaticReflectionInterface
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
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
    }

    /**
     * @return \ReflectionClass
     */
    private function _parseParentClass()
    {
        return $this->_context->getClass( $this->_parseClassOrInterfaceName() );
    }

    /**
     * @return array(\ReflectionClass)
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
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
    }

    /**
     * @return \ReflectionClass
     */
    private function _parseInterface()
    {
        return $this->_context->getClass( $this->_parseClassOrInterfaceName() );
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
                        $this->_context->getPathname( $this->_className )
                    );
            }
        }
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
    }

    /**
     * Parses a method declaration for/from the currently parsed class/interface.
     *
     * @param string  $docComment Optional doc comment for the parsed method.
     * @param integer $modifiers  Bitfield with method modifiers.
     *
     * @return \org\pdepend\reflection\api\StaticReflectionClass
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
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
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
                        $this->_context->getPathname( $this->_className )
                    );
            }
            $this->_consumeComments();
        }
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
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
        throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
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
     * @return \org\pdepend\reflection\parser\Token
     */
    private function _consumeToken( $tokenType )
    {
        if ( is_object( $token = $this->_next() ) === false )
        {
            throw new EndOfTokenStreamException( $this->_context->getPathname( $this->_className ) );
        }
        if ( $token->type !==  $tokenType )
        {
            throw new UnexpectedTokenException(
                $token,
                $this->_context->getPathname( $this->_className )
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
     * @return \org\pdepend\reflection\parser\Token
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
     * @return \org\pdepend\reflection\parser\Token
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