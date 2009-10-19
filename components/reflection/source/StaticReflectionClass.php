<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

use de\buzz2ee\reflection\interfaces\ReflectionClass;

/**
 * Static class implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionClass extends StaticReflectionInterface
{
    const TYPE = __CLASS__;

    /**
     * @var integer
     */
    private $_modifiers = 0;

    /**
     * @var \de\buzz2ee\reflection\interfaces\ReflectionClass
     */
    private $_parentClass = null;

    /**
     * @var array(\de\buzz2ee\reflection\interfaces\ReflectionProperty)
     */
    private $_properties = null;

    /**
     * @param string  $name
     * @param string  $docComment
     * @param integer $modifiers
     */
    public function __construct( $name, $docComment = '', $modifiers = 0 )
    {
        parent::__construct( $name, $docComment );

        $this->_modifiers = $modifiers;
    }

    /**
     * Returns <b>true</b> when the class is declared abstract or is an interface.
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return ( ( $this->_modifiers & self::IS_ABSTRACT ) === self::IS_ABSTRACT );
    }

    /**
     * Returns <b>true</b> when the class is declared as final.
     *
     * @return boolean
     */
    public function isFinal()
    {
        return ( ( $this->_modifiers & self::IS_FINAL ) === self::IS_FINAL );
    }

    /**
     * @return boolean
     */
    public function isInterface()
    {
        return false;
    }

    /**
     * @return \de\buzz2ee\reflection\interfaces\ReflectionClass
     */
    public function getParentClass()
    {
        return $this->_parentClass;
    }

    /**
     * @param \de\buzz2ee\reflection\interfaces\ReflectionClass $parentClass
     *
     * @return void
     */
    public function setParentClass( ReflectionClass $parentClass )
    {
        if ( $this->_parentClass === null )
        {
            $this->_parentClass = $parentClass;
        }
        else
        {
            throw new \RuntimeException( 'Parent class already set.' );
        }
    }

    /**
     * @param string $name
     *
     * @return \de\buzz2ee\reflection\interfaces\ReflectionProperty
     */
    public function getProperty( $name )
    {
        if ( isset( $this->_properties[$name] ) )
        {
            return $this->_properties[$name];
        }
        throw new \RuntimeException( sprintf( 'Property %s does not exist', $name ) );
    }

    /**
     * @return array(\de\buzz2ee\reflection\interfaces\ReflectionProperties)
     */
    public function getProperties()
    {
        return $this->_properties;
    }

    /**
     * @param array(\de\buzz2ee\reflection\interfaces\ReflectionProperties) $properties
     *
     * @return void
     */
    public function setProperties( array $properties )
    {
        if ( $this->_properties === null )
        {
            $this->_properties = array();
            foreach ( $properties as $property )
            {
                $property->initDeclaringClass( $this );
                $this->_properties[$property->getName()] = $property;
            }
        }
        else
        {
            throw new \RuntimeException( 'Properties already set.' );
        }
    }
}