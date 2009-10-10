<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc\interfaces;

/**
 * Abstract base implementation of a property argument.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BasePropertyArgument implements PropertyArgument
{
    /**
     * @var string
     */
    private $_propertyName = null;

    /**
     * @param string $propertyName
     */
    protected function __construct( $propertyName )
    {
        $this->_propertyName = $propertyName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_propertyName;
    }

    /**
     * @param ObjectFactory $factory
     *
     * @return void
     */
    public function configure( ObjectFactory $factory )
    {
        $factory->registerPropertyArgument( $this );
    }
}