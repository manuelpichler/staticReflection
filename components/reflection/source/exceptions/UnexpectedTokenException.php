<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\exceptions;

use org\pdepend\reflection\parser\Token;

/**
 * This type of exception will be thrown when the parser detects an invalid
 * source token.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class UnexpectedTokenException extends ParserException
{
    /**
     * Constructs a new unexpected token exception.
     *
     * @param \org\pdepend\reflection\parser\Token $token    The unexpected token.
     * @param string                              $fileName The parsed source file.
     */
    public function __construct( Token $token, $fileName )
    {
        parent::__construct(
            sprintf(
                'Unexpected token[image="%s", type=%d] on line %d in file %s',
                $token->image,
                $token->type,
                $token->startLine,
                $fileName
            )
        );
    }
}