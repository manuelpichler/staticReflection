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

use org\pdepend\reflection\api\DefaultValue;
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
     * The source file path name.
     *
     * @var string
     */
    private $_pathName = null;

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
     * Reference to the currently parsed class or interface.
     *
     * @var \org\pdepend\reflection\api\StaticReflectionInterface
     */
    private $_classOrInterface = null;
    
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
     * Parsed method parameters for a single method.
     *
     * @var array(\org\pdepend\reflection\api\StaticReflectionParameter)
     */
    private $_parameters = array();

    /**
     * Static variables declared within a method's body.
     *
     * @var array(string=>mixed)
     */
    private $_staticVariables = array();

    /**
     * Constructs a new parser instance.
     *
     * @param \org\pdepend\reflection\parser\ParserContext $context
     */
    public function __construct( ParserContext $context )
    {
        $this->_context   = $context;
    }

    /**
     * @return \org\pdepend\reflection\api\StaticReflectionInterface
     */
    public function parseClass( $className )
    {
        $normalizedName = $this->_normalizeName( $className );

        $classes = $this->parseFile( $this->_context->getPathname( $className ) );
        foreach ( $classes as $class )
        {
            if ( $this->_normalizeName( $class->getName() ) === $normalizedName )
            {
                return $class;
            }
        }
        throw new \ReflectionException( 'Class ' . $className . ' does not exist' );
    }

    public function parseFile( $pathName )
    {
        $this->_pathName = $pathName;
        return $this->parseSource( file_get_contents( $this->_pathName ) );
    }

    public function parseSource( $source )
    {
        return $this->_parse( $source );
    }

    /**
     * @return array(\org\pdepend\reflection\api\StaticReflectionInterface)
     */
    private function _parse( $source )
    {
        $this->_tokenizer = new Tokenizer( $source );

        $class      = null;
        $classes    = array();
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
                    $modifiers |= StaticReflectionClass::IS_EXPLICIT_ABSTRACT;
                    break;

                case ParserTokens::T_FINAL:
                    $modifiers |= StaticReflectionClass::IS_FINAL;
                    break;

                case ParserTokens::T_CLASS:
                    $class = $this->_parseClassDeclaration( $docComment, $modifiers );
                    break;

                case ParserTokens::T_INTERFACE:
                    $class = $this->_parseInterfaceDeclaration( $docComment );
                    break;
            }

            if ( $class === null )
            {
                continue;
            }

            $class->initStartLine( $token->startLine );
            $class->initFileName( $this->_pathName );

            array_push( $classes, $class );

            $class      = null;
            $modifiers  = 0;
            $docComment = '';
        }
        return $classes;
    }

    /**
     *
     * @return void
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
                    throw new UnexpectedTokenException( $token, $this->_pathName );
            }
        }
        throw new EndOfTokenStreamException( $this->_pathName );
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
        throw new EndOfTokenStreamException( $this->_pathName );
    }

    /**
     * @return \org\pdepend\reflection\api\StaticReflectionClass
     */
    private function _parseClassDeclaration( $docComment, $modifiers )
    {
        $this->_classOrInterface = null;

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_STRING:
                    $name = $this->_createClassOrInterfaceName( array( $token->image ) );

                    $this->_classOrInterface = new StaticReflectionClass( $name, $docComment, $modifiers );
                    break;

                case ParserTokens::T_EXTENDS:
                    $this->_classOrInterface->initParentClass( $this->_parseParentClass() );
                    break;

                case ParserTokens::T_IMPLEMENTS:
                    $this->_classOrInterface->initInterfaces( $this->_parseInterfaceList() );
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    $endLine = $this->_parseClassOrInterfaceScope();

                    $this->_classOrInterface->initEndLine( $endLine );
                    $this->_classOrInterface->initMethods( $this->_methods );
                    $this->_classOrInterface->initConstants( $this->_constants );
                    $this->_classOrInterface->initProperties( $this->_properties );
                    
                    return $this->_classOrInterface;
            }
        }
        throw new EndOfTokenStreamException( $this->_pathName );
    }

    /**
     * @return \org\pdepend\reflection\api\StaticReflectionInterface
     */
    private function _parseInterfaceDeclaration( $docComment )
    {
        $this->_classOrInterface = null;

        while ( is_object( $token = $this->_next() ) )
        {
            switch ( $token->type )
            {
                case ParserTokens::T_STRING:
                    $name = $this->_createClassOrInterfaceName( array( $token->image ) );

                    $this->_classOrInterface = new StaticReflectionInterface( $name, $docComment );
                    break;

                case ParserTokens::T_EXTENDS:
                    $this->_classOrInterface->initInterfaces( $this->_parseInterfaceList() );
                    break;

                case ParserTokens::T_SCOPE_OPEN:
                    $endLine = $this->_parseClassOrInterfaceScope( StaticReflectionMethod::IS_ABSTRACT );

                    $this->_classOrInterface->initEndLine( $endLine );
                    $this->_classOrInterface->initMethods( $this->_methods );
                    $this->_classOrInterface->initConstants( $this->_constants );

                    return $this->_classOrInterface;
            }
        }
        throw new EndOfTokenStreamException( $this->_pathName );
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
                case ParserTokens::T_NAMESPACE:
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
        throw new EndOfTokenStreamException( $this->_pathName );
    }

    /**
     * @return \ReflectionClass
     */
    private function _parseInterface()
    {
        $className = $this->_parseClassOrInterfaceName();
        if ( $className === $this->_classOrInterface->getName() )
        {
            return $this->_classOrInterface;
        }
        return $this->_context->getClass( $className );
    }

    /**
     * @return string
     */
    private function _parseClassOrInterfaceName()
    {
        return $this->_createClassOrInterfaceName( $this->_parseIdentifier() );
    }

    private function _parseIdentifier()
    {
        $name = array();

        $this->_consumeComments();
        if ( $this->_peek() === ParserTokens::T_NAMESPACE )
        {
            $this->_consumeToken( ParserTokens::T_NAMESPACE );

            $name[] = '\\';
            $name[] = $this->_namespace;

            $this->_consumeComments();
            $name[] = $this->_consumeToken( ParserTokens::T_NS_SEPARATOR )->image;
        }
        else if ( $this->_peek() === ParserTokens::T_NS_SEPARATOR )
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
        return $name;
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
                    throw new UnexpectedTokenException( $this->_next(), $this->_pathName );
            }
        }
        throw new EndOfTokenStreamException( $this->_pathName );
    }

    /**
     * Parses a method declaration for/from the currently parsed class/interface.
     *
     * @param string  $docComment Optional doc comment for the parsed method.
     * @param integer $modifiers  Bitfield with method modifiers.
     *
     * @return void
     */
    private function _parseMethodDeclaration( $docComment, $modifiers )
    {
        $this->_staticVariables = array();

        $startLine  = $this->_consumeToken( ParserTokens::T_FUNCTION )->startLine;
        $returnsRef = $this->_parseOptionalByReference();

        $this->_consumeComments();
        $token = $this->_consumeToken( ParserTokens::T_STRING );

        $this->_methods[] = new StaticReflectionMethod( $token->image, $docComment, $modifiers);

        $this->_parseMethodParameterList();

        $this->_consumeComments();
        if ( $this->_peek() === ParserTokens::T_SEMICOLON )
        {
            $endLine = $this->_consumeToken( ParserTokens::T_SEMICOLON )->endLine;
        }
        else
        {
            $endLine = $this->_parseScope()->endLine;
        }

        $method = end( $this->_methods );
        $method->initStartLine( $startLine );
        $method->initEndLine( $endLine );
        $method->initParameters( $this->_parameters );
        $method->initStaticVariables( $this->_staticVariables );

        if ( $returnsRef )
        {
            $method->initReturnsReference();
        }
    }

    /**
     * Parses the signature of a method, which means everything starting from
     * the opening <b>(</b> parenthesis until the closing parenthesis <b>)</b>.
     *
     * @return void
     */
    private function _parseMethodParameterList()
    {
        $this->_consumeComments();
        $this->_consumeToken( ParserTokens::T_BLOCK_OPEN );

        $this->_parameters = array();
        while ( $this->_parseMethodParameter() )
        {
            $this->_consumeComments();
            if ( $this->_peek() !== ParserTokens::T_COMMA )
            {
                break;
            }
            $this->_consumeToken( ParserTokens::T_COMMA );
        }
        $this->_consumeToken( ParserTokens::T_BLOCK_CLOSE );
    }

    /**
     * Parses a single method parameter and returns <b>true</b> when a parameter
     * was found. Otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    private function _parseMethodParameter()
    {
        $typeHint = $this->_parseOptionalMethodParameterTypeHint();
        $byRef    = $this->_parseOptionalByReference();

        $this->_consumeComments();
        if ( $this->_peek() !== ParserTokens::T_VARIABLE )
        {
            return false;
        }

        $token = $this->_consumeToken( ParserTokens::T_VARIABLE );

        $parameter = new StaticReflectionParameter( $token->image, count( $this->_parameters ) );
        $parameter->initDefaultValue( $this->_parseOptionalDefaultValue() );

        if ( $byRef )
        {
            $parameter->initPassedByReference();
        }
        if ( $typeHint )
        {
            $parameter->initTypeHint( $typeHint );
        }

        $this->_parameters[] = $parameter;

        return true;
    }

    /**
     * Parses an optional parameter type-hint and returns a reflection class
     * instance for a type type-hint, <b>true</b> for an array type-hint or
     * <b>false</b> when no type hint exists.
     *
     * @return \ReflectionClass|boolean
     */
    private function _parseOptionalMethodParameterTypeHint()
    {
        $this->_consumeComments();
        switch ( $this->_peek() )
        {
            case ParserTokens::T_ARRAY:
                $this->_consumeToken( ParserTokens::T_ARRAY );
                return true;

            case ParserTokens::T_STRING:
            case ParserTokens::T_NAMESPACE:
            case ParserTokens::T_NS_SEPARATOR:
                return $this->_parseInterface();
        }
        return false;
    }

    /**
     * Parses an optional by reference flag from the token stream and returns
     * <b>true</b> when a by reference token was found, otherwise the return
     * value will be <b>false</b>.
     *
     * @return boolean
     */
    private function _parseOptionalByReference()
    {
        $this->_consumeComments();
        if ( $this->_peek() === ParserTokens::T_BITWISE_AND )
        {
            $this->_consumeToken( ParserTokens::T_BITWISE_AND );
            $this->_consumeComments();
            return true;
        }
        return false;
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

    /**
     * Parses a single property declaration.
     *
     * @param string  $docComment The doc comment for the next property.
     * @param integer $modifiers  The specified property modifiers.
     *
     * @return void
     */
    private function _parsePropertyDeclaration( $docComment, $modifiers )
    {
        $this->_consumeComments();
        $token = $this->_consumeToken( ParserTokens::T_VARIABLE );

        $property = new StaticReflectionProperty( $token->image, $docComment, $modifiers );
        $property->initValue( $this->_parseOptionalDefaultValue() );
        
        $this->_properties[] = $property;
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

    /**
     * This method parses a single constant declaration.
     *
     * @return void
     */
    private function _parseConstantDeclaration()
    {
        $this->_consumeComments();
        $token = $this->_consumeToken( ParserTokens::T_STRING );

        $this->_consumeComments();
        $this->_consumeToken( ParserTokens::T_EQUAL );

        $this->_constants[$token->image] = $this->_parseStaticScalar();
    }

    /**
     * Parses an optional default as it can occure for property or parameter
     * nodes.
     *
     * @return \org\pdepend\reflection\api\DefaultValue
     */
    private function _parseOptionalDefaultValue()
    {
        $this->_consumeComments();
        if ( $this->_peek() === ParserTokens::T_EQUAL )
        {
            $this->_consumeToken( ParserTokens::T_EQUAL );
            return $this->_parseDefaultValue();
        }
        return null;
    }

    /**
     * Parses an optional default as it can occure for property or parameter
     * nodes.
     *
     * @return \org\pdepend\reflection\api\DefaultValue
     */
    private function _parseDefaultValue()
    {
        return new DefaultValue( $this->_parseStaticScalarOrArray() );
    }

    private function _parseStaticScalarOrArray()
    {
        $this->_consumeComments();
        if ( $this->_peek() === ParserTokens::T_ARRAY )
        {
            return $this->_parseStaticArray();
        }
        return $this->_parseStaticScalar();
    }

    private function _parseStaticArray()
    {
        $this->_consumeComments();
        $this->_consumeToken( ParserTokens::T_ARRAY );

        $this->_consumeComments();
        $this->_consumeToken( ParserTokens::T_BLOCK_OPEN );

        $array = array();

        while ( ( $tokenType = $this->_peek() ) !== Tokenizer::EOF )
        {
            if ( $tokenType === ParserTokens::T_BLOCK_CLOSE )
            {
                break;
            }

            $keyOrValue = $this->_parseStaticScalarOrArray();

            $this->_consumeComments();
            if ( $this->_peek() === ParserTokens::T_COMMA )
            {
                $array[] = $keyOrValue;
            }
            else if ( $this->_peek() !== ParserTokens::T_BLOCK_CLOSE )
            {
                $array[$keyOrValue] = $this->_parseStaticScalarOrArray();
            }
            
            $this->_consumeComments();
            if ( $this->_peek() === ParserTokens::T_COMMA )
            {
                $this->_consumeToken( ParserTokens::T_COMMA );
            }
            
            $this->_consumeComments();
        }

        $this->_consumeComments();
        $this->_consumeToken( ParserTokens::T_BLOCK_CLOSE );

        return $array;
    }

    private function _parseStaticScalar()
    {
        $value = null;

        $this->_consumeComments();
        switch ( $this->_peek() )
        {
            case ParserTokens::T_FILE:
                $this->_consumeToken( ParserTokens::T_FILE );
                return $this->_pathName;

            case ParserTokens::T_LINE:
                return $this->_consumeToken( ParserTokens::T_LINE )->startLine;

            case ParserTokens::T_CLASS_C:
                $this->_consumeToken( ParserTokens::T_CLASS_C );
                return $this->_classOrInterface->getName();

            case ParserTokens::T_NS_C:
                $this->_consumeToken( ParserTokens::T_NS_C );
                return $this->_classOrInterface->getNamespaceName();

            case ParserTokens::T_FUNCTION_C:
                $this->_consumeToken( ParserTokens::T_FUNCTION_C );
                return end( $this->_methods )->getName();

            case ParserTokens::T_METHOD_C:
                $this->_consumeToken( ParserTokens::T_METHOD_C );
                return sprintf(
                    '%s::%s',
                    $this->_classOrInterface->getName(),
                    end( $this->_methods )->getName()
                );

            case ParserTokens::T_NULL;
                $this->_consumeToken( ParserTokens::T_NULL );
                return null;

            case ParserTokens::T_TRUE:
                $this->_consumeToken( ParserTokens::T_TRUE );
                return true;

            case ParserTokens::T_FALSE:
                $this->_consumeToken( ParserTokens::T_FALSE );
                return false;

            case ParserTokens::T_TEXT:
                $text = $this->_consumeToken( ParserTokens::T_TEXT )->image;
                $text = str_replace( '\\' . substr( $text, 0, 1 ), substr( $text, 0, 1 ), $text );
                return substr( $text, 1, -1 );

            case ParserTokens::T_DNUMBER:
                return (float) $this->_consumeToken( ParserTokens::T_DNUMBER )->image;

            case ParserTokens::T_LNUMBER:
                return (int) $this->_consumeToken( ParserTokens::T_LNUMBER )->image;

            case ParserTokens::T_SELF:
            case ParserTokens::T_PARENT:
                $value = '__StaticReflectionConstantValue(';

                $value .= $this->_consumeToken( $this->_peek() )->image;
                $this->_consumeComments();
                $value .= $this->_consumeToken( ParserTokens::T_DOUBLE_COLON )->image;
                $this->_consumeComments();
                $value .= $this->_consumeToken( ParserTokens::T_STRING )->image;
               
                $value .= ')';
                return $value;

            case ParserTokens::T_STRING:
                $parts = $this->_parseIdentifier();

                $this->_consumeComments();

                $value = '__StaticReflectionConstantValue(';
                if ( $this->_peek() === ParserTokens::T_DOUBLE_COLON )
                {
                    $value .= $this->_createClassOrInterfaceName( $parts );
                    $value .= $this->_consumeToken( ParserTokens::T_DOUBLE_COLON )->image;
                    $this->_consumeComments();
                    $value .= $this->_consumeToken( ParserTokens::T_STRING )->image;
                }
                else if ( count( $parts ) === 1 )
                {
                    $value .= array_shift( $parts );
                }
                else
                {
                    $value .= $this->_createClassOrInterfaceName( $parts );
                }
                $value .= ')';
                return $value;

            case ParserTokens::T_NAMESPACE;
            case ParserTokens::T_NS_SEPARATOR;
                $value = '__StaticReflectionConstantValue(';

                $value .= $this->_parseClassOrInterfaceName();

                $this->_consumeComments();
                if ( $this->_peek() === ParserTokens::T_DOUBLE_COLON )
                {
                    $value .= $this->_consumeToken( ParserTokens::T_DOUBLE_COLON )->image;
                    $this->_consumeComments();
                    $value .= $this->_consumeToken( ParserTokens::T_STRING )->image;
                }
                $value .= ')';
                return $value;

            default:
                throw new UnexpectedTokenException( $this->_next(), $this->_pathName );
        }
    }

    private function _parseScope()
    {
        $this->_consumeComments();
        $this->_consumeToken( ParserTokens::T_SCOPE_OPEN );

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

                case ParserTokens::T_STATIC:
                    $this->_consumeComments();
                    if ( $this->_peek() === ParserTokens::T_VARIABLE )
                    {
                        $this->_parseStaticVariables();
                    }
                    break;
            }

            if ( $scope === 0 )
            {
                return $token;
            }
        }
        throw new EndOfTokenStreamException( $this->_pathName );
    }

    private function _parseStaticVariables()
    {
        do
        {
            $this->_parseStaticVariable();
            $this->_consumeComments();

            if ( $this->_peek() === ParserTokens::T_COMMA )
            {
                $this->_consumeToken( ParserTokens::T_COMMA );
                $this->_consumeComments();
            }
            else
            {
                break;
            }
        }
        while ( $this->_peek() === ParserTokens::T_VARIABLE );

        $this->_consumeToken( ParserTokens::T_SEMICOLON );
    }

    private function _parseStaticVariable()
    {
        $this->_consumeComments();

        $name  = $this->_consumeToken( ParserTokens::T_VARIABLE )->image;
        $value = $this->_parseOptionalDefaultValue();

        if ( is_object( $value ) )
        {
            $this->_staticVariables[$name] = $value->getData();
        }
        else
        {
            $this->_staticVariables[$name] = null;
        }
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
            throw new EndOfTokenStreamException( $this->_pathName );
        }
        if ( $token->type !==  $tokenType )
        {
            throw new UnexpectedTokenException( $token, $this->_pathName );
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

    /**
     * Normalizes a class or interface name.
     *
     * @param string $name A class or interface name.
     *
     * @return string
     */
    private function _normalizeName( $name )
    {
        if ( function_exists( 'mb_strtolower' ) )
        {
            return ltrim( mb_strtolower( $name ), '\\' );
        }
        // @codeCoverageIgnoreStart
        return ltrim( strtolower( $name ), '\\' );
        // @codeCoverageIgnoreEnd
    }
}