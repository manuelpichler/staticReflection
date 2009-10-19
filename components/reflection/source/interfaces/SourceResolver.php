<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection\interfaces;

/**
 * Base interface for a source resolver.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface SourceResolver
{
    /**
     * Returns the file pathname where the given class is defined.
     *
     * @param string $className
     *
     * @return string
     */
    function getPathnameForClass( $className );

    /**
     * Returns the source of the file where the given class is defined.
     *
     * @param string $className
     *
     * @return string
     */
    function getSourceForClass( $className );
}