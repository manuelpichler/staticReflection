<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

use com\example\ioc\interfaces\Container;
use com\example\ioc\interfaces\BasePropertyArgument;

/**
 * Property argument that injects a simple scalar or object value.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PropertyValueArgument extends BasePropertyArgument
{
    /**
     * @var mixed
     */
    private $_value = null;

    /**
     * @param string $propertyName
     * @param mixed  $propertyValue
     */
    public function __construct( $propertyName, $propertyValue )
    {
        parent::__construct( $propertyName );

        $this->_value = $propertyValue;
    }

    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function getValue( Container $container )
    {
        return $this->_value;
    }
}