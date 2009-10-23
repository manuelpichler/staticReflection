<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection\parser;

/**
 * The default parser tokens.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
final class ParserTokens
{
    /**
     * Supported parser tokens.
     */
    const T_ABSTRACT     = -1,
          T_AS           = -2,
          T_CLASS        = -3,
          T_COMMA        = -4,
          T_CONST        = -5,
          T_DOC_COMMENT  = -6,
          T_FINAL        = -7,
          T_EXTENDS      = -8,
          T_FUNCTION     = -9,
          T_IMPLEMENTS   = -10,
          T_INTERFACE    = -11,
          T_NAMESPACE    = -12,
          T_NS_SEPARATOR = -13,
          T_PRIVATE      = -14,
          T_PROTECTED    = -15,
          T_PUBLIC       = -16,
          T_SEMICOLON    = -17,
          T_SCOPE_CLOSE  = -18,
          T_SCOPE_OPEN   = -19,
          T_STATIC       = -20,
          T_STRING       = -21,
          T_USE          = -22,
          T_VARIABLE     = -23;
}