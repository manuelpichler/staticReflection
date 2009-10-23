<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

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
     * @var \ReflectionClass
     */
    private $_parentClass = null;

    /**
     * @var array(\ReflectionProperty)
     */
    private $_properties = null;

    /**
     * @param string  $name
     * @param string  $docComment
     * @param integer $modifiers
     */
    public function __construct( $name, $docComment, $modifiers )
    {
        parent::__construct( $name, $docComment );

        $this->_modifiers = $modifiers;
    }

    public function getModifiers()
    {
        if ( count( $this->getMethods( StaticReflectionMethod::IS_ABSTRACT ) ) > 0 )
        {
            return $this->_modifiers | self::IS_IMPLICIT_ABSTRACT;
        }
        return $this->_modifiers;
    }

    /**
     * Returns <b>true</b> when the class is declared abstract or is an interface.
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return ( $this->_isExplicitAbstract() || $this->_isImplicitAbstract() );
    }

    /**
     * Returns <b>true</b> when the explicit abstract modifier is present.
     *
     * @return boolean
     */
    private function _isExplicitAbstract()
    {
        return ( ( $this->getModifiers() & self::IS_EXPLICIT_ABSTRACT ) === self::IS_EXPLICIT_ABSTRACT );
    }

    /**
     * Returns <b>true</b> when the implicit abstract modifier is present.
     *
     * @return boolean
     */
    private function _isImplicitAbstract()
    {
        return ( ( $this->getModifiers() & self::IS_IMPLICIT_ABSTRACT ) === self::IS_IMPLICIT_ABSTRACT );
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
     * Returns <b>true</b> when the reflected class/interface is an interface,
     * which means that this concrete implementation always returns <b>false</b>.
     * 
     * @return boolean
     */
    public function isInterface()
    {
        return false;
    }

    /**
     * @return \ReflectionClass
     */
    public function getParentClass()
    {
        return $this->_parentClass;
    }

    /**
     * @param \ReflectionClass $parentClass
     *
     * @return void
     * @access private
     */
    public function initParentClass( \ReflectionClass $parentClass )
    {
        if ( $this->_parentClass === null )
        {
            $this->_parentClass = $parentClass;
        }
        else
        {
            throw new \LogicException( 'Property parentClass already set' );
        }
    }

    public function getMethods( $filter = -1 )
    {
        if ( $this->_parentClass === null )
        {
            return parent::getMethods( $filter );
        }
        return $this->_collectMethodsFromParentClass( $filter );
    }

    private function _collectMethodsFromParentClass( $filter )
    {
        $result = parent::collectMethods();
        foreach ( $this->_parentClass->getMethods() as $method )
        {
            $result = $this->_collectMethodFromParentClass( $method, $result );
        }
        return $this->prepareCollectedMethods( $filter, $result );
    }

    private function _collectMethodFromParentClass( \ReflectionMethod $method, array $result )
    {
        $name = strtolower( $method->getName() );
        if ( !isset( $result[$name] ) )
        {
            $result[$name] = $method;
        }
        else if ( $result[$name]->isAbstract() && !$method->isAbstract() )
        {
            $result[$name] = $method;
        }
        return $result;
    }

    public function getConstructor()
    {
        
    }

    /**
     * @param string $name
     *
     * @return \ReflectionProperty
     */
    public function getProperty( $name )
    {
        if ( isset( $this->_properties[$name] ) )
        {
            return $this->_properties[$name];
        }
        throw new \ReflectionException( sprintf( 'Property %s does not exist', $name ) );
    }

    /**
     * @return array(\ReflectionProperty)
     */
    public function getProperties( $filter = 0 )
    {
        return $this->_properties;
    }

    /**
     * Tries to initializes the properties of the reflected class the first time,
     * it will throw an exception when the properties are already set .
     *
     * @param array(\ReflectionProperty) $properties The properties of this class.
     *
     * @return void
     * @access private
     */
    public function initProperties( array $properties )
    {
        if ( $this->_properties === null )
        {
            $this->_initProperties( $properties );
        }
        else
        {
            throw new \LogicException( 'Property properties already set' );
        }
    }

    /**
     * Initializes the properties of the reflected class.
     *
     * @param array(\ReflectionProperty) $properties The properties of this class.
     *
     * @return void
     */
    private function _initProperties( array $properties )
    {
        $this->_properties = array();
        foreach ( $properties as $property )
        {
            $property->initDeclaringClass( $this );
            $this->_properties[$property->getName()] = $property;
        }
    }
}