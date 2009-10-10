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
 * Prototype based object factory that will create a new instance of the
 * corresponding class for each request.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class PrototypeObjectFactory extends BaseObjectFactory
{
    /**
     * @param Container $container
     *
     * @return stdClass
     */
    public function create( Container $container )
    {
        return $this->createObject( $container );
    }
}