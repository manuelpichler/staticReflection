<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc\interfaces;

/**
 * Base interface for a class source file loader.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface SourceLoader
{
    /**
     * Ensures that the source for the given class name is present.
     *
     * @param string $className Name of the class to load.
     *
     * @return boolean
     */
    function load( $className );
}