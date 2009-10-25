<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\interfaces;

/**
 * Interface with different class, method and/op property modifiers.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface Reflector
{
    /**
     * The available modifiers
     */
    const IS_ABSTRACT  = 2,
          IS_FINAL     = 4,
          IS_PRIVATE   = 1024,
          IS_PROTECTED = 512,
          IS_PUBLIC    = 256,
          IS_STATIC    = 1;
}