<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace com\example\aop\pointcut;

use com\example\aop\interfaces\PointcutMatcher;
use com\example\aop\exceptions\InvalidPointcutExpressionException;

/**
 * The default pointcut parser implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PointcutExpressionParser
{
    const REGEXP_POINTCUT_NAMED = '(^
                (?P<class>([\\]?[a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*)+)::
                (?P<method>[a-z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\(\)
            $)xi',
          REGEXP_COMBINE_OPERATORS   = '((&&|\|\|))',
          REGEXP_POINTCUT_DESIGNATOR = '(^
                (?P<designator>execution)\((?P<expression>.+)\)
            $)x',
          REGEXP_POINTCUT_EXPRESSION = '(^
                ((?P<visibility>public|protected|private|\*)\s+)?
                ((?P<class>([\\]?[a-z_\x7f-\xff\*][a-z0-9_\x7f-\xff\*]*)+)::)
                ((?P<method>([\\]?[a-z_\x7f-\xff\*][a-z0-9_\x7f-\xff\*]*)+)\(\))
            $)xi';

    private $_expression = null;

    private $_offset = -1;

    private $_tokens = array();

    /**
     * @param string $expression
     *
     * @return PointcutExpression
     */
    public function parse( $expression )
    {
        $this->_expression = $expression;

        $this->_offset = -1;
        $this->_tokens = $this->_splitCombinedExpressions( $expression );

        if ( count( $this->_tokens ) % 2 === 0 )
        {
            throw new InvalidPointcutExpressionException( $expression );
        }
        return $this->_parsePointcutBinaryMatcher();
    }

    /**
     * @param string $expression
     *
     * @return array(string)
     */
    private function _splitCombinedExpressions( $expression )
    {
        return array_map(
            'trim',
            preg_split(
                self::REGEXP_COMBINE_OPERATORS,
                $expression,
                -1,
                PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
            )
        );
    }

    private function _parsePointcutBinaryMatcher()
    {
        $expression = $this->_parsePointcutNotMatcher( $this->_tokens[++$this->_offset] );

        if ( isset( $this->_tokens[++$this->_offset] ) )
        {
            if ( $this->_tokens[$this->_offset] === '&&' )
            {
                $expression = PointcutMatcherFactory::get()->createAndMatcher(
                    $expression, $this->_parsePointcutBinaryMatcher()
                );
            }
            else if ( $this->_tokens[$this->_offset] === '||' )
            {
                $expression = PointcutMatcherFactory::get()->createOrMatcher(
                    $expression, $this->_parsePointcutBinaryMatcher()
                );
            }
        }

        return $expression;
    }

    /**
     * @param string $expression
     *
     * @return PointcutMatcher
     */
    private function _parsePointcutNotMatcher( $expression )
    {
        if ( substr( $expression, 0, 1 ) === '!' )
        {
            return PointcutMatcherFactory::get()->createNotMatcher(
                $this->_parsePointcutNotMatcher(
                    trim( substr( $expression, 1 ) )
                )
            );
        }
        return $this->_parsePointcutMatcher( $expression );
    }

    /**
     * @param string $expression
     *
     * @return PointcutMatcher
     */
    private function _parsePointcutMatcher( $expression )
    {
        if ( preg_match( self::REGEXP_POINTCUT_NAMED, $expression, $match ) )
        {
            return PointcutMatcherFactory::get()->createNamedMatcher(
                $expression,
                $match['class'],
                $match['method']
            );
        }
        else if ( preg_match( self::REGEXP_POINTCUT_DESIGNATOR, $expression, $match ) )
        {
            return $this->_parseDesignator( 
                $match['designator'],
                $match['expression']
            );
        }
        throw new InvalidPointcutExpressionException( $this->_expression );
    }

    /**
     * @param string $designator
     * @param string $expression
     *
     * @return PointcutMatcher
     */
    private function _parseDesignator( $designator, $expression )
    {
        switch ( $designator )
        {
            case 'execution':
                $matcher = $this->_parseExecutionDesignator( $expression );
                break;
        }
        return $matcher;
    }

    /**
     * @param string $expression
     *
     * @return PointcutExecutionMatcher
     */
    private function _parseExecutionDesignator( $expression )
    {
        if ( preg_match( self::REGEXP_POINTCUT_EXPRESSION, $expression, $match ) )
        {
            return PointcutMatcherFactory::get()->createExecutionMatcher(
                $match['class'],
                $match['method'],
                $match['visibility']
            );
        }
        throw new InvalidPointcutExpressionException( $this->_expression );
    }
}