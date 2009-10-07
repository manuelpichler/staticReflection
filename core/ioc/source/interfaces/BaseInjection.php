<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc\interfaces;

/**
 * Abstract base implementation of a property injection.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BaseInjection implements Injection
{
    /**
     * @var stdClass
     */
    private $_object = null;

    /**
     * @var string
     */
    private $_name = null;

    /**
     * @param stdClass $object
     * @param string   $name
     */
    public function __construct( $object, $name )
    {
        $this->_object = $object;
        $this->_name   = $name;
    }

    /**
     * @return ReflectionObject
     */
    protected function getObject()
    {
        return $this->_object;
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->_name;
    }
}