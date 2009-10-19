<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

/**
 * Static method implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionMethod extends \ReflectionMethod
{
    /**
     * @var string
     */
    private $_name = null;

    /**
     * @var string
     */
    private $_docComment = false;

    /**
     * @var integer
     */
    private $_modifiers = 0;

    /**
     * The declaring class.
     *
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
        $this->_setName( $name );
        $this->_setModifiers( $modifiers );
        $this->_setDocComment( $docComment );
    }

    /**
     * Returns the name of the reflected method.
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name of the reflected method.
     *
     * @param string $name Name of the reflected method.
     *
     * @return void
     */
    private function _setName( $name )
    {
        $this->_name = $name;
    }

    /**
     * Returns the method's short name.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->_name;
    }

    /**
     * Get the namespace name where the class is defined.
     *
     * @return string
     */
    public function getNamespaceName()
    {
        return '';
    }

    /**
     * Returns the doc comment of the reflected method or <b>false</b> when no
     * comment was found.
     *
     * @return string|boolean
     */
    public function getDocComment()
    {
        return $this->_docComment;
    }

    /**
     * Sets the doc comment of the reflected method.
     *
     * @param string $docComment Doc comment for the reflected method.
     *
     * @return void
     */
    private function _setDocComment( $docComment )
    {
        if ( trim( $docComment ) !== '' )
        {
            $this->_docComment = $docComment;
        }
    }

    /**
     * Returns the modifiers of the reflected method.
     *
     * @return integer
     */
    public function getModifiers()
    {
        return $this->_modifiers;
    }

    /**
     * Sets and validates the modifiers of the reflected method.
     *
     * @param integer $modifiers The modifiers for the reflected method.
     *
     * @return void
     */
    public function _setModifiers( $modifiers )
    {
        $expected = self::IS_PRIVATE | self::IS_PROTECTED | self::IS_PUBLIC
                  | self::IS_STATIC  | self::IS_ABSTRACT  | self::IS_FINAL;

        if ( ( $modifiers & ~$expected ) !== 0 )
        {
            throw new \ReflectionException( 'Invalid method modifier given.' );
        }
        $this->_modifiers = $modifiers;
    }

    /**
     * Returns the pathname where the reflected method was declared.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->getDeclaringClass()->getFileName();
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        return ( ( $this->_modifiers & self::IS_ABSTRACT ) === self::IS_ABSTRACT );
    }

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return ( ( $this->_modifiers & self::IS_STATIC ) === self::IS_STATIC );
    }

    /**
     * @return boolean
     */
    public function isFinal()
    {
        return ( ( $this->_modifiers & self::IS_FINAL ) === self::IS_FINAL );
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return ( ( $this->_modifiers & self::IS_PRIVATE ) === self::IS_PRIVATE );
    }

    /**
     * @return boolean
     */
    public function isProtected()
    {
        return ( ( $this->_modifiers & self::IS_PROTECTED ) === self::IS_PROTECTED );
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return ( ( $this->_modifiers & self::IS_PUBLIC ) === self::IS_PUBLIC );
    }

    /**
     * Returns <b>true</b> when the reflected method is declared by an internal
     * class/interface, or <b>false</b> when it is user-defined.
     *
     * @return boolean
     */
    public function isInternal()
    {
        return false;
    }

    /**
     * Returns <b>true</b> when the reflected method is user-defined, otherwise
     * this method will return <b>false</b>.
     *
     * @return boolean
     */
    public function isUserDefined()
    {
        return true;
    }

    /**
     * Gets the declaring class.
     *
     * @return \ReflectionClass
     */
    public function getDeclaringClass()
    {
        return $this->_declaringClass;
    }

    /**
     * Sets the <b>ReflectionClass</b> where the reflected method is declared.
     *
     * @param \ReflectionClass $declaringClass The class where the reflected
     *        method is declared.
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
            throw new \LogicException( 'Declaring class already set' );
        }
    }
}