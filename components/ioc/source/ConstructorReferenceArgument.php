<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

use de\buzz2ee\ioc\interfaces\Container;
use de\buzz2ee\ioc\interfaces\BaseConstructorArgument;

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
    /**
     * @var string
     */
    private $_lookupKey = null;

    /**
     * @param string $lookupKey
     */
    public function __construct( $lookupKey )
    {
        $this->_lookupKey = $lookupKey;
    }

    /**
     * @param Container $container
     *
     * @return stdClass
     */
    public function getValue( Container $container )
    {
        return $container->lookup( $this->_lookupKey );
    }
}