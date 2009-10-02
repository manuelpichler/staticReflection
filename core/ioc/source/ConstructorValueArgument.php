<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

/**
 * Constructor argument implementation that will return a simple value.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ConstructorValueArgument extends ConstructorArgument
{
    private $_value = null;

    public function __construct( $value )
    {
        $this->_value = $value;
    }

    public function getValue( Container $container )
    {
        return $this->_value;
    }
}