<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc\interfaces;

/**
 * Base interface of a property injection.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface Injection
{
    /**
     * Injects the argument value into a propery of the context object.
     *
     * @param array(Argument) $arguments
     *
     * @return void
     */
    function inject( array $arguments );
}