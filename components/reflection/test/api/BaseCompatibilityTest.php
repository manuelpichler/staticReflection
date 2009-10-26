<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\api;

use org\pdepend\reflection\parser\Parser;

require_once 'BaseTest.php';

/**
 * Abstract base test for api compatiblility between the static reflection
 * implementation and PHP's native api.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BaseCompatibilityTest extends \org\pdepend\reflection\BaseTest
{
    /**
     * Creates an internal reflection class instance.
     *
     * @param string $className Name of the searched class.
     *
     * @return \ReflectionClass
     */
    protected function createInternalClass( $className )
    {
        $this->includeClass( $className );
        return new \ReflectionClass( $className );
    }

    /**
     * Creates an internal reflection class instance.
     *
     * @param string $className Name of the searched class.
     *
     * @return \ReflectionClass
     */
    protected function createStaticClass( $className )
    {
        $parser = new Parser( $this->createParserContext(), $className );
        return $parser->parse();
    }
}