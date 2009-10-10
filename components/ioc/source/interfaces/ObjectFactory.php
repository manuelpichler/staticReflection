<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc\interfaces;

/**
 * Base interface for all object factory implementations.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface ObjectFactory
{
    /**
     * @param Argument $argument
     *
     * @return void
     */
    function registerConstructorArgument( Argument $argument );

    /**
     * @param PropertyArgument $argument
     *
     * @return void
     */
    function registerPropertyArgument( PropertyArgument $argument );

    /**
     * @param Container $container
     *
     * @return stdClass
     */
    function create( Container $container );
}