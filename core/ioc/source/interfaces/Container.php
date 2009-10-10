<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc\interfaces;

/**
 * Base interface for an ioc container implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface Container
{
    function registerSingleton( $lookupKey, $className, array $args = array() );

    function registerPrototype( $lookupKey, $className, array $args = array() );

    function lookup( $lookupKey );
}