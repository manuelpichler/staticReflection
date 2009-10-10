<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

use de\buzz2ee\ioc\interfaces\Argument;
use de\buzz2ee\ioc\interfaces\Container;
use de\buzz2ee\ioc\interfaces\BaseInjection;

/**
 * Injection strategy for simple object properties.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ReflectionPropertyInjection extends BaseInjection
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
        if ( count( $arguments ) === 0 )
        {
            throw new \RuntimeException( 'Bad argument count for property injection.' );
        }
        else if ( count( $arguments ) > 1 )
        {
            throw new \ErrorException( 'Bad argument count for property injection.' );
        }

        $this->getObject()->{$this->getName()} = reset( $arguments );
    }
}