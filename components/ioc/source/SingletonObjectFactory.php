<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

use de\buzz2ee\ioc\interfaces\Container;
use de\buzz2ee\ioc\interfaces\BaseObjectFactory;

/**
 * Object factory implementation that acts as a singleton for an unique instance
 * of the corresponding class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class SingletonObjectFactory extends BaseObjectFactory
{
    /**
     * @var stdClass
     */
    private $_instance = null;

    /**
     * @param Container $container
     *
     * @return stdClass
     */
    public function create( Container $container )
    {
        if ( $this->_instance === null )
        {
            $this->_instance = $this->createObject( $container );
        }
        return $this->_instance;
    }
}