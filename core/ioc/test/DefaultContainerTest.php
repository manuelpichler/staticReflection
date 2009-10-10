<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

require_once 'BaseContainerTest.php';

/**
 * Test case for the default container implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class DefaultContainerTest extends BaseContainerTest
{
    /**
     * @return Container
     */
    protected function createContainer()
    {
        return new DefaultContainer();
    }
}
