<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

use de\buzz2ee\reflection\interfaces\ReflectionClass;

/**
 * Static interface implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionInterface implements ReflectionClass
{
    /**
     * @var string
     */
    private $_name = null;

    /**
     * @var string
     */
    private $_docComment = null;

    /**
     * @var array(\de\buzz2ee\reflection\interfaces\ReflectionMethod)
     */
    private $_methods = null;

    /**
     * @var array(\de\buzz2ee\reflection\interfaces\ReflectionClass)
     */
    private $_interfaces = null;

    /**
     * @param string $name
     * @param string $docComment
     */
    public function __construct( $name, $docComment = '' )
    {
        $this->_name       = $name;
        $this->_docComment = $docComment;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getDocComment()
    {
        return $this->_docComment;
    }

    /**
     * @return boolean
     */
    public function isInterface()
    {
        return true;
    }

    /**
     * @return array(\de\buzz2ee\reflection\interfaces\ReflectionClass)
     */
    public function getInterfaces()
    {
        return (array) $this->_interfaces;
    }

    /**
     * @param array(\de\buzz2ee\reflection\interfaces\ReflectionClass) $interfaces
     *
     * @return void
     */
    public function setInterfaces( array $interfaces )
    {
        if ( $this->_interfaces === null )
        {
            $this->_interfaces = $interfaces;
        }
        else
        {
            throw new \RuntimeException( 'Interfaces already set' );
        }
    }

    /**
     * @return \de\buzz2ee\reflection\interfaces\ReflectionClass
     */
    public function getParentClass()
    {
        return null;
    }

    /**
     * @return array(\de\buzz2ee\reflection\interfaces\ReflectionMethod)
     */
    public function getMethods()
    {
        return (array) $this->_methods;
    }

    /**
     * @param array(\de\buzz2ee\reflection\interfaces\ReflectionMethod) $methods
     *
     * @return void
     */
    public function setMethod( array $methods )
    {
        if ( $this->_methods === null )
        {
            $this->_methods = $methods;
        }
        else
        {
            throw new \RuntimeException( 'Methods already set' );
        }
    }

    /**
     * @return array(\de\buzz2ee\reflection\interfaces\ReflectionProperty)
     */
    public function getProperties()
    {
        return array();
    }
}