<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection\interfaces;

/**
 * The default parser tokens.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface ParserTokens
{
    /**
     * Supported parser tokens.
     */
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
          T_STATIC       = -19,
          T_STRING       = -20,
          T_USE          = -21,
          T_VARIABLE     = -22;
}