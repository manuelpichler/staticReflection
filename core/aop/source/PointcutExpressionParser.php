<?php
/**
 * I provide completely working code within this framework, which may not be
 * developed any further, because there are already existing packages, which
 * try to provide similar functionallities.
 */

namespace com\example\aop;

use com\example\aop\interfaces\PointcutMatcher;
use com\example\aop\pointcut\PointcutNotMatcher;
use com\example\aop\pointcut\PointcutNamedMatcher;
use com\example\aop\pointcut\PointcutExecutionMatcher;
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
                (([\\]?[a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*)+)::
                ([a-z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\(\)
            $)xi',
          REGEXP_COMBINE_OPERATORS   = '((&&|\|\|))',
          REGEXP_POINTCUT_DESIGNATOR = '(^(execution)\((.+)\)$)',
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
        return $this->_parseCombinedExpression();
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

    private function _parseCombinedExpression()
    {
        $expression = $this->_parseExpressionOrSignatureWithOptionalNot( $this->_tokens[++$this->_offset] );

        if ( isset( $this->_tokens[++$this->_offset] ) )
        {
            if ( $this->_tokens[$this->_offset] === '&&' )
            {
                $expression = new PointcutAndMatcher(
                    $expression, $this->_parseCombinedExpression()
                );
            }
            else if ( $this->_tokens[$this->_offset] === '||' )
            {
                $expression = new PointcutOrMatcher(
                    $expression, $this->_parseCombinedExpression()
                );
            }
            else
            {
                throw new InvalidPointcutExpressionException( $this->_expression );
            }
        }

        return $expression;
    }

    private function _parseExpressionOrSignatureWithOptionalNot( $expression )
    {
        if ( substr( $expression, 0, 1 ) === '!' )
        {
            return new PointcutNotMatcher(
                $this->_parseExpressionOrSignatureWithOptionalNot(
                    trim( substr( $expression, 1 ) )
                )
            );
        }
        return $this->_parseExpressionOrSignature( $expression );
    }

    private function _parseExpressionOrSignature( $expression )
    {
        if ( preg_match( self::REGEXP_POINTCUT_NAMED, $expression, $match ) )
        {
            return new PointcutNamedMatcher( $expression, $match[1], $match[2] );
        }
        else if ( preg_match( self::REGEXP_POINTCUT_DESIGNATOR, $expression, $match ) )
        {
            return $this->_parseDesignator( $match[1], $match[2] );
        }
        throw new InvalidPointcutExpressionException( $this->_expression );
    }

    private function _parseDesignator( $designator, $expression )
    {
        switch ( $designator )
        {
            case 'execution':
                return $this->_parseExecutionDesignator( $expression );
        }
    }

    private function _parseExecutionDesignator( $expression )
    {
        preg_match( self::REGEXP_POINTCUT_EXPRESSION, $expression, $match );
        var_dump($expression, $match);

        return new PointcutExecutionMatcher(
            $match['class'],
            $match['method'],
            $match['visibility']
        );
    }

}





abstract class PointcutBinaryMatcher implements PointcutMatcher
{
    /**
     * @var PointcutMatcher
     */
    private $_matcherLeft  = null;

    /**
     * @var PointcutMatcher
     */
    private $_matcherRight = null;

    public function __construct( PointcutMatcher $left, PointcutMatcher $right )
    {
        $this->_matcherLeft  = $left;
        $this->_matcherRight = $right;
    }

    /**
     * @return PointcutMatcher
     */
    protected function getLeftMatcher()
    {
        return $this->_matcherLeft;
    }

    /**
     * @return PointcutMatcher
     */
    protected function getRightMatcher()
    {
        return $this->_matcherRight;
    }
}

class PointcutAndMatcher extends PointcutBinaryMatcher
{
    /**
     * @param string $className
     * @param string $methodName
     *
     * @return boolean
     */
    public function match( $className, $methodName )
    {
        return (
            $this->getLeftMatcher()->match( $className, $methodName ) &&
            $this->getRightMatcher()->match( $className, $methodName )
        );
    }
}

class PointcutOrMatcher extends PointcutBinaryMatcher
{
    /**
     * @param string $className
     * @param string $methodName
     *
     * @return boolean
     */
    public function match( $className, $methodName )
    {
        return (
            $this->getLeftMatcher()->match( $className, $methodName ) ||
            $this->getRightMatcher()->match( $className, $methodName )
        );
    }
}