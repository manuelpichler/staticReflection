<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc\exceptions;

/**
 * Exception that will be thrown when an unknown property was configured.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PropertyNotFoundException extends Exception
{
    /**
     * @param stdClass $object
     * @param string   $property 
     */
    public function __construct( $object, $property )
    {
        parent::__construct(
            sprintf( 'No property %s::%s found.', get_class( $object ), $property )
        );
    }
}