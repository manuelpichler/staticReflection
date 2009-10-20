<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

/**
 * Static property implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionProperty extends \ReflectionProperty
{
    const TYPE = __CLASS__;

    /**
     * @var string
     */
    private $_name = null;

    /**
     * @var string|boolean
     */
    private $_docComment = false;

    /**
     * @var integer
     */
    private $_modifiers = 0;

    /**
     * @var \ReflectionClass
     */
    private $_declaringClass = null;

    /**
     * @param string  $name
     * @param string  $docComment
     * @param integer $modifiers
     */
    public function __construct( $name, $docComment, $modifiers )
    {
        $this->setName( $name );
        $this->setModifiers( $modifiers );
        $this->setDocComment( $docComment );
    }

    /**
     * Returns the class where the reflected property was declared.
     *
     * @return \ReflectionClass
     */
    public function getDeclaringClass()
    {
        return $this->_declaringClass;
    }

    /**
     * Sets the declaring class for the context property. Note that this method
     * is only for internal usage.
     *
     * @param \ReflectionClass $declaringClass The declaring class.
     *
     * @return void
     * @access private
     */
    public function initDeclaringClass( \ReflectionClass $declaringClass )
    {
        if ( $this->_declaringClass === null )
        {
            $this->_declaringClass = $declaringClass;
        }
        else
        {
            throw new \ReflectionException( 'Declaring class was already set' );
        }
    }

    /**
     * Returns the name of the reflected property.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the reflected property. A leading <b>$</b> will be
     * stripped from the property name.
     *
     * @param string $name The property name.
     *
     * @return void
     */
    protected function setName( $name )
    {
        $this->_name = ltrim( $name, '$' );
    }

    /**
     * Returns the doc comment of the reflected property or <b>false</b> when
     * no doc comment exists.
     *
     * @return string|boolean
     */
    public function getDocComment()
    {
        return $this->_docComment;
    }

    /**
     * Sets the doc comment for the reflected property. This method will only
     * set the doc comment attribute when the given value is not empty.
     *
     * @param string $docComment The doc comment for the reflected property.
     *
     * @return void
     */
    protected function setDocComment( $docComment )
    {
        if ( ( $comment = trim( $docComment ) ) !== '' )
        {
            $this->_docComment = $comment;
        }
    }

    /**
     * Returns a numeric representation of the modifiers for the reflected
     * property.
     * 
     * @return integer
     */
    public function getModifiers()
    {
        return $this->_modifiers;
    }

    /**
     * Sets the numeric representation of the modifiers for the reflected
     * property.
     *
     * @param integer $modifiers Numeric representation of the modifiers.
     *
     * @return void
     */
    protected function setModifiers( $modifiers )
    {
        $expected = self::IS_PRIVATE | self::IS_PROTECTED | self::IS_PUBLIC | self::IS_STATIC;

        if ( ( $modifiers & ~$expected ) !== 0 )
        {
            throw new \ReflectionException( 'Invalid property modifier given.' );
        }
        $this->_modifiers = $modifiers;
    }

    /**
     * Returns <b>true</b> when the reflected property was declared as static,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isStatic()
    {
        return ( ( $this->_modifiers & self::IS_STATIC ) === self::IS_STATIC );
    }

    /**
     * Returns <b>true</b> when the reflected property was declared as private,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isPrivate()
    {
        return ( ( $this->_modifiers & self::IS_PRIVATE ) === self::IS_PRIVATE );
    }

    /**
     * Returns <b>true</b> when the reflected property was declared as protected,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isProtected()
    {
        return ( ( $this->_modifiers & self::IS_PROTECTED ) === self::IS_PROTECTED );
    }

    /**
     * Returns <b>true</b> when the reflected property was declared as public,
     * otherwise this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isPublic()
    {
        return ( ( $this->_modifiers & self::IS_PUBLIC ) === self::IS_PUBLIC );
    }

    /**
     * Checks if the reflected property is default and declared at compile time
     * and returns <b>true</b>, otherwise the returned value is <b>false</b> if
     * it was created at run-time.
     *
     * @return boolean
     */
    public function isDefault()
    {
        return true;
    }

    /**
     * Returns the value of the context property.
     *
     * @param object $object The object being reflected.
     *
     * @return mixed
     */
    public function getValue( $object = null )
    {
        if ( $object === null )
        {
            return null;
        }
        throw new \ReflectionException( 'Method getValue() is not supported' );
    }

    /**
     * Sets the value of the context property.
     *
     * @param object $object The object being reflected.
     * @param mixed  $value  The new property value.
     *
     * @return void
     */
    public function setValue( $object, $value )
    {
        throw new \ReflectionException( 'Method setValue() is not supported' );
    }

    /**
     * Sets a property to be accessible. For example, it may allow protected and
     * private properties to be accessed.
     *
     * @param boolean $accessible <b>true</b> to allow accessibility, or <b>false</b>.
     *
     * @return void
     */
    public function setAccessible( $accessible )
    {
        throw new \ReflectionException( 'Method setAccessible() is not supported' );
    }

    public function __toString()
    {
        return '';
    }
}