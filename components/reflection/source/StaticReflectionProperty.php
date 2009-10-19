<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

use de\buzz2ee\reflection\interfaces\ReflectionProperty;

/**
 * Static property implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionProperty implements ReflectionProperty
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
     * @var integer
     */
    private $_modifiers = 0;

    /**
     * @param string  $name
     * @param string  $docComment
     * @param integer $modifiers
     */
    public function __construct( $name, $docComment, $modifiers )
    {
        $this->_name       = $name;
        $this->_docComment = $docComment;
        $this->_modifiers  = $modifiers;
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
     * @return integer
     */
    public function getModifiers()
    {
        return $this->_modifiers;
    }

    /**
     * @return boolean
     */
    public function isFinal()
    {
        return ( $this->_modifiers & self::IS_FINAL === self::IS_FINAL );
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return ( $this->_modifiers & self::IS_PRIVATE === self::IS_PRIVATE );
    }

    /**
     * @return boolean
     */
    public function isProtected()
    {
        return ( $this->_modifiers & self::IS_PROTECTED === self::IS_PROTECTED );
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return ( $this->_modifiers & self::IS_PUBLIC === self::IS_PUBLIC );
    }
}