<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

/**
 * Static interface implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionInterface extends \ReflectionClass
{
    const TYPE = __CLASS__;

    /**
     * Common zend engine constants
     */
    const ZEND_ACC_INTERFACE = 0x80;

    /**
     * @var string
     */
    private $_name = null;

    /**
     * Doc comment for the reflected interface.
     * 
     * @var string
     */
    private $_docComment = false;

    /**
     * The file pathname where this interface is defined.
     *
     * @var string
     */
    private $_fileName = null;

    /**
     * The start line number where the interface declaration starts.
     *
     * @var integer
     */
    private $_startLine = -1;

    /**
     * The end line number where the interface declaration ends.
     *
     * @var integer
     */
    private $_endLine = -1;

    /**
     * Declared interface methods.
     *
     * @var array(\ReflectionMethod)
     */
    private $_methods = null;

    /**
     * Declared class/interface constants.
     *
     * @var array(string=>mixed)
     */
    private $_constants = null;

    /**
     * Extended/Implemented interfaces.
     *
     * @var array(\ReflectionClass)
     */
    private $_interfaces = null;

    /**
     * Constructs a new static reflection interface instance.
     *
     * @param string $name       Qualified name of the reflected interface.
     * @param string $docComment Doc comment for this interface.
     */
    public function __construct( $name, $docComment )
    {
        $this->_setName( $name );
        $this->_setDocComment( $docComment );
    }

    /**
     * Returns the file pathname where this interface was defined.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     * Sets the file pathname where this interface was defined. Note that this
     * method is only for internal use.
     *
     * @param string $fileName File pathname where the interface was defined.
     *
     * @return void
     * @access private
     */
    public function initFileName( $fileName )
    {
        if ( $this->_fileName === null )
        {
            $this->_fileName = $fileName;
        }
        else
        {
            throw new \LogicException( 'Property fileName already set.' );
        }
    }

    /**
     * Returns the qualified name of the reflected interface.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the qualified name of the reflected interface.
     *
     * @param string $name The full qualified interface name.
     *
     * @return void
     */
    private function _setName( $name )
    {
        $this->_name = ltrim( $name, '\\' );
    }

    /**
     * Returns the short name of the class, the part without the namespace.
     *
     * @return string
     */
    public function getShortName()
    {
        if ( ( $pos = strrpos( $this->_name, '\\' ) ) === false )
        {
            return $this->_name;
        }
        return substr( $this->_name, $pos + 1 );
    }

    /**
     * Returns the namespace name of the reflected interface.
     *
     * @return string
     */
    public function getNamespaceName()
    {
        if ( ( $pos = strrpos( $this->_name, '\\' ) ) === false )
        {
            return '';
        }
        return substr( $this->_name, 0, $pos );
    }

    /**
     * Checks if this class is defined in a namespace. 
     *
     * @return boolean
     */
    public function inNamespace()
    {
        return ( $this->getNamespaceName() !== '' );
    }

    /**
     * Returns the doc comment for the reflected interface or <b>false</b> when
     * no doc comment exists.
     *
     * @return string|boolean
     */
    public function getDocComment()
    {
        return $this->_docComment;
    }

    /**
     * Sets the doc comment for the reflected interface.
     *
     * @param string $docComment Doc comment for this interface.
     *
     * @return void
     */
    private function _setDocComment( $docComment )
    {
        if ( trim( $docComment ) === '' )
        {
            $this->_docComment = false;
        }
        else
        {
            $this->_docComment = $docComment;
        }
    }

    /**
     * Returns the class/interface modifiers
     *
     * @return integer
     */
    public function getModifiers()
    {
        return self::ZEND_ACC_INTERFACE;
    }

    /**
     * Returns <b>true</b> when the class is declared abstract or is an interface.
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return true;
    }

    /**
     * Returns <b>true</b> when the class is declared as final.
     *
     * @return boolean
     */
    public function isFinal()
    {
        return false;
    }

    /**
     * Returns <b>true</b> when the reflected interface/class is an interface,
     * in this case it means that this method always returns <b>true</b>.
     *
     * @return boolean
     */
    public function isInterface()
    {
        return true;
    }

    /**
     * Checks whether the class is internal, as opposed to user-defined.
     *
     * @return boolean
     */
    public function isInternal()
    {
        return false;
    }

    /**
     * Checks whether the class is user-defined, as opposed to internal. 
     *
     * @return boolean
     */
    public function isUserDefined()
    {
        return true;
    }

    /**
     * Checks if a class is an instance of an object.
     *
     * @param object $object The object to check.
     *
     * @return boolean
     */
    public function isInstance( $object )
    {
        return ( $object instanceof $this->_name );
    }

    /**
     * Checks if the class is instantiable.
     *
     * @return boolean
     */
    public function isInstantiable()
    {
        return false;
    }

    public function isIterateable()
    {
        
    }

    public function isSubclassOf( $class )
    {
        
    }

    public function hasConstant( $name )
    {
        return array_key_exists( $name, $this->getConstants() );
    }

    public function getConstant( $name )
    {
        if ( $this->hasConstant( $name ) )
        {
            $constants = $this->getConstants();
            return $constants[$name];
        }
        return false;
    }

    public function getConstants()
    {
        return $this->_collectConstants( $this, (array) $this->_constants );
    }

    private function _collectConstants( \ReflectionClass $class, array $constantList )
    {
        foreach ( $class->getInterfaces() as $interface )
        {
            $constantList = $this->_collectConstants( $interface, $constantList );
        }
        return $constantList;
    }

    /**
     * Initializes the declared constants for the reflected class/interface.
     *
     * @param array(string=>mixed) $contants Declared class/interface constants.
     *
     * @return void
     * @access private
     */
    public function initConstants( array $contants )
    {
        if ( $this->_constants === null )
        {
            $this->_constants = $contants;
        }
        else
        {
            throw new \LogicException( 'Property constants already set' );
        }
    }

    /**
     * Checks whether it implements an interface. 
     *
     * @param string $interface The interface name.
     *
     * @return boolean
     */
    public function implementsInterface( $interface )
    {
        if ( strcasecmp( $this->_name, $interface ) === 0 )
        {
            return true;
        }
        return in_array(
            strtolower( $interface ),
            array_map( 'strtolower', $this->getInterfaceNames() )
        );
    }

    /**
     * Returns an array with the names of all implemented/extended interfaces.
     *
     * @return array(string)
     */
    public function getInterfaceNames()
    {
        $names = array();
        foreach ( $this->getInterfaces() as $interface )
        {
            $names[] = $interface->getName();
        }
        return $names;
    }

    /**
     * Returns an array with all implemented/extended interfaces.
     *
     * @return array(\ReflectionClass)
     */
    public function getInterfaces()
    {
        return array_values( $this->_collectInterfaces( (array) $this->_interfaces ) );
    }

    /**
     * Collects recursive all implemented/extended interfaces.
     *
     * @param array(\ReflectionClass)         $interfaces    Input interface list.
     * @param array(string=>\ReflectionClass) $interfaceList Result list
     *
     * @return array(\ReflectionClass)
     */
    private function _collectInterfaces( array $interfaces, array &$interfaceList = array() )
    {
        foreach ( $interfaces as $interface )
        {
            if ( isset( $interfaceList[$interface->getName()] ) === false )
            {
                $interfaceList[$interface->getName()] = $interface;
                $this->_collectInterfaces( $interface->getInterfaces(), $interfaceList );
            }
        }
        return $interfaceList;
    }

    /**
     * @param array(\ReflectionClass) $interfaces
     *
     * @return void
     * @access private
     */
    public function initInterfaces( array $interfaces )
    {
        if ( $this->_interfaces === null )
        {
            $this->_interfaces = $interfaces;
        }
        else
        {
            throw new \LogicException( 'Interfaces already set' );
        }
    }

    /**
     * @return \ReflectionClass|boolean
     */
    public function getParentClass()
    {
        return false;
    }

    /**
     * Returns the constructor of the reflected interface, what means <b>null</b>
     * because an interface has no constructor.
     *
     * @return \ReflectionClass
     */
    public function getConstructor()
    {
        return null;
    }

    /**
     * Checks whether a specific method is defined in a class.
     *
     * @param string $name Name of the method being checked for.
     *
     * @return boolean
     */
    public function hasMethod( $name )
    {
        return isset( $this->_methods[strtolower( $name )] ) || $this->_hasMethod( $name );
    }

    /**
     * Checks whether a specific method is defined in one of the parent interfaces.
     *
     * @param string $name Name of the method being checked for.
     *
     * @return boolean
     */
    private function _hasMethod( $name )
    {
        foreach ( $this->getInterfaces() as $interface )
        {
            if ( $interface->hasMethod( $name ) )
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets a <b>ReflectionMethod</b> about a method.
     *
     * @param string $name The method name to reflect.
     *
     * @return \ReflectionMethod
     */
    public function getMethod( $name )
    {
        if ( isset( $this->_methods[strtolower( $name )] ) )
        {
            return $this->_methods[strtolower( $name )];
        }
        return $this->_getMethodFromParentInterfaces( $name );
    }

    /**
     * Gets a <b>ReflectionMethod</b> about a method from one of the parent
     * interfaces.
     *
     * @param string $name The method name to reflect.
     *
     * @return \ReflectionMethod
     */
    private function _getMethodFromParentInterfaces( $name )
    {
        foreach ( $this->getInterfaces() as $interface )
        {
            if ( $interface->hasMethod( $name ) )
            {
                return $interface->getMethod( $name );
            }
        }
        throw new \ReflectionException( sprintf( 'Method %s does not exist', $name ) );
    }

    /**
     * Returns an array with all methods within the inheritence hierarchy of this
     * class or interface.
     *
     * @return array(\ReflectionMethod)
     */
    public function getMethods( $filter = -1 )
    {
        return $this->prepareCollectedMethods( $filter, $this->collectMethods() );
    }

    protected function collectMethods()
    {
        $result = $this->_collectMethods( (array) $this->_methods );
        foreach ( $this->getInterfaces() as $interface )
        {
            $result = $this->_collectMethods( $interface->getMethods(), $result );
        }
        return $result;
    }

    /**
     * Collects all methods from <b>$methods</b> and adds those methods to the
     * <b>&$result</b> that do not already exist in this array,
     *
     * @param array(string=>\ReflectionMethod) $methods Input methods.
     * @param array(string=>\ReflectionMethod) $result  Resulting method array.
     *
     * @return array(string=>\ReflectionMethod)
     */
    private function _collectMethods( array $methods, array $result = array() )
    {
        foreach ( $methods as $method )
        {
            $result = $this->_collectMethod( $method, $result );
        }
        return $result;
    }

    /**
     * Adds the given method <b>$method</b> to <b>&$result</b> when a method
     * with same name does not exist in this array,
     *
     * @param \ReflectionMethod                $method Input method.
     * @param array(string=>\ReflectionMethod) $result Resulting method array.
     *
     * @return array(string=>\ReflectionMethod)
     */
    private function _collectMethod( \ReflectionMethod $method, array $result )
    {
        $name = strtolower( $method->getName() );
        if ( !isset( $result[$name] ) )
        {
            $result[$name] = $method;
        }
        return $result;
    }

    protected function prepareCollectedMethods( $filter, array $methods )
    {
        if ( $filter === -1 )
        {
            return array_values( $methods );
        }
        return $this->_filterCollectedMethods( $filter, $methods );
    }

    private function _filterCollectedMethods( $filter, array $methods )
    {
        $result = array();
        foreach ( $methods as $method )
        {
            if ( ( $method->getModifiers() & $filter ) === $filter )
            {
                $result[] = $method;
            }
        }
        return $result;
    }

    /**
     * This method initializes the methods that are declared for the reflected
     * class or interface.
     *
     * @param array(\ReflectionMethod) $methods The declared class/interface methods.
     *
     * @return void
     * @access private
     */
    public function initMethods( array $methods )
    {
        if ( $this->_methods === null )
        {
            $this->_methods = array();
            foreach ( $methods as $method )
            {
                $method->initDeclaringClass( $this );
                $this->_methods[strtolower( $method->getName() )] = $method;
            }
        }
        else
        {
            throw new \LogicException( 'Methods already set' );
        }
    }

    /**
     * Checks whether the specified property is defined.
     *
     * @param string $name Name of the property being checked for.
     * 
     * @return boolean
     */
    public function hasProperty( $name )
    {
        return false;
    }

    /**
     * Gets a property.
     *
     * @param string $name The property name.
     *
     * @return \ReflectionProperty
     */
    public function getProperty( $name )
    {
        throw new \ReflectionException( sprintf( 'Property %s does not exist', $name ) );
    }

    /**
     * @return array(\ReflectionProperty)
     */
    public function getProperties( $filter = -1 )
    {
        return array();
    }

    /**
     * Gets default properties from a class.
     *
     * @return array(mixed)
     */
    public function getDefaultProperties()
    {
        return array();
    }

    /**
     * Gets the static property values.
     *
     * @param string $name    The property name.
     * @param mixed  $default Optional default value.
     *
     * @return mixed
     */
    public function getStaticPropertyValue( $name, $default = null )
    {
        throw new \ReflectionException(
            sprintf( 'Class %s does not have a property named %s', $this->_name, $name )
        );
    }

    /**
     * Get the static properties.
     *
     * @return array(string=>mixed)
     */
    public function getStaticProperties()
    {
        return array();
    }

    /**
     * Sets static property value.
     *
     * @param string $name  The property name.
     * @param mixed  $value The new property value.
     *
     * @return void
     */
    public function setStaticPropertyValue( $name, $value )
    {
        throw new \ReflectionException( 'Method setStaticPropertyValue() is not supported' );
    }

    /**
     * Get the starting line number. 
     *
     * @return integer
     */
    public function getStartLine()
    {
        return $this->_startLine;
    }

    /**
     * Initializes the start line number. Note that this method is only used
     * internally by this component.
     *
     * @param integer $startLine Line number where the interface declaration starts.
     *
     * @return void
     * @access private
     */
    public function initStartLine( $startLine )
    {
        if ( $this->_startLine === -1 )
        {
            $this->_startLine = $startLine;
        }
        else
        {
            throw new \LogicException( 'Property startLine already set.' );
        }
    }

    /**
     * Get the ending line number.
     *
     * @return integer
     */
    public function getEndLine()
    {
        return $this->_endLine;
    }

    /**
     * Initializes the end line number. Note that this method is only used
     * internally by this component.
     *
     * @param integer $endLine Line number where the interface declaration ends.
     *
     * @return void
     * @access private
     */
    public function initEndLine( $endLine )
    {
        if ( $this->_endLine === -1 )
        {
            $this->_endLine = $endLine;
        }
        else
        {
            throw new \LogicException( 'Property endLine already set.' );
        }
    }

    /**
     * Gets an extensions <b>\ReflectionExtension</b> object or <b>null</b>.
     *
     * @return \ReflectionExtension
     */
    public function getExtension()
    {
        return null;
    }

    /**
     * Returns the name of the owning extension or <b>false</b>.
     *
     * @return string|boolean
     */
    public function getExtensionName()
    {
        return false;
    }

    /**
     * Creates an instance of the context class.
     *
     * @param mixed $args Accepts a variable number of arguments which are
     *                    passed to the function much like call_user_func().
     *
     * @return object
     */
    public function newInstance( $args )
    {
        throw new \ReflectionException( 'Method newInstance() is not supported' );
    }

    /**
     * Creates an instance of the context class.
     *
     * @param array(mixed) $args Arguments which are passed to the constructor
     *                           much like call_user_func_array().
     *
     * @return object
     */
    public function newInstanceArgs( array $args = array() )
    {
        throw new \ReflectionException( 'Method newInstanceArgs() is not supported' );
    }

    public function __toString()
    {
        return '';
    }
}