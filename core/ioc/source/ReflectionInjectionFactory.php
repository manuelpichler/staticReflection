<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

use com\example\ioc\exceptions\PropertyNotFoundException;

/**
 * This factory can be used to create argument validating injection objects.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ReflectionInjectionFactory
{
    /**
     * @param stdClass $object
     * @param string   $property
     *
     * @return Injection
     */
    public function create( $object, $property )
    {
        if ( isset( $object->$property ) || property_exists( $object , $property ) )
        {
            return new ReflectionPropertyInjection( $object, $property );
        }
        else if ( method_exists( $object, 'set' . $property ) )
        {
            return new ReflectionSetterInjection( $object, 'set' . $property );
        }
        throw new PropertyNotFoundException( $object, $property );
    }
}