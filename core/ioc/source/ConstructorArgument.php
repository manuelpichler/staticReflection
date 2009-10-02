<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

/**
 * Abstract base implementation of a constructor argument.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class ConstructorArgument implements Argument
{
    public function configure( ObjectFactory $factory )
    {
        $factory->registerConstructorArgument( $this );
    }
}
