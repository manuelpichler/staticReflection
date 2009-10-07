<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc\interfaces;

/**
 * Base interface for an object builder implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface ObjectBuilder
{
    /**
     * Builds a concrete object instance.
     *
     * @param Container $container
     *
     * @return stdClass
     */
    function build( Container $container );

    /**
     * @param Argument $argument
     *
     * @return void
     */
    function addConstructorArgument( Argument $argument );

    /**
     * @param PropertyArgument $argument
     *
     * @return void
     */
    function addPropertyArgument( PropertyArgument $argument );
}