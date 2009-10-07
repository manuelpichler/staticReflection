<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

use \com\example\ioc\interfaces\SourceLoader;

/**
 * Class source loader implementation that relies on PHP's autoload functionallity.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class SourceLoaderAutoload implements SourceLoader
{
    /**
     * Ensures that the source for the given class name is present.
     *
     * @param string $className Name of the class to load.
     *
     * @return boolean
     */
    public function load( $className )
    {
        return class_exists( $className, true );
    }
}