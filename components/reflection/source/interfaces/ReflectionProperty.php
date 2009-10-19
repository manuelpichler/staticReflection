<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection\interfaces;

/**
 * Base interface for all reflection property implementations.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface ReflectionProperty extends Reflector
{
    /**
     * @return string
     */
    function getName();

    /**
     * @return string
     */
    function getDocComment();

    /**
     * @return integer
     */
    function getModifiers();

    /**
     * @return boolean
     */
    function isStatic();

    /**
     * @return boolean
     */
    function isPrivate();

    /**
     * @return boolean
     */
    function isProtected();

    /**
     * @return boolean
     */
    function isPublic();
}