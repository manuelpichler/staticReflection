<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

use com\example\ioc\interfaces\Argument;
use com\example\ioc\interfaces\Container;
use com\example\ioc\interfaces\BaseInjection;
use com\example\ioc\exceptions\MethodNotFoundException;

/**
 * Injection implementation that uses setter methods on an object
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ReflectionSetterInjection extends BaseInjection
{
    /**
     * Injects the argument value into a propery of the context object.
     *
     * @param array(Argument) $arguments
     *
     * @return void
     */
    public function inject( array $arguments )
    {
        $validator = ArgumentValidatorFactory::get()->create( get_class( $this->getObject() ) );
        $validator->validate( $this->getName(), $arguments );

        call_user_func_array( array( $this->getObject(), $this->getName() ), $arguments );
    }
}