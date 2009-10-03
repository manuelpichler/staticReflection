<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

use com\example\ioc\interfaces\Container;
use com\example\ioc\interfaces\BaseConstructorArgument;

/**
 * Constructor argument implementation that will return a object reference from
 * the ioc container.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ConstructorReferenceArgument extends BaseConstructorArgument
{
    private $_lookupKey = null;

    public function __construct( $lookupKey )
    {
        $this->_lookupKey = $lookupKey;
    }

    public function getValue( Container $container )
    {
        return $container->lookup( $this->_lookupKey );
    }
}