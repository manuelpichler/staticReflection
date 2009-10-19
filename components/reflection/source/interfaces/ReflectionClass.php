<?php
/**
 * I provide completely working code with this framework, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection\interfaces;

/**
 * Base interface for all reflection classes.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
interface ReflectionClass extends Reflector
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
     * Returns <b>true</b> when the class is declared abstract or is an interface.
     *
     * @return boolean
     */
    function isAbstract();

    /**
     * Returns <b>true</b> when the class is declared as final.
     *
     * @return boolean
     */
    function isFinal();

    /**
     * @return boolean
     */
    function isInterface();

    /**
     * @return array(\de\buzz2ee\reflection\interfaces\ReflectionClass)
     */
    function getInterfaces();

    /**
     * @return \de\buzz2ee\reflection\interfaces\ReflectionClass
     */
    function getParentClass();

    /**
     * @param string $name
     *
     * \de\buzz2ee\reflection\interfaces\ReflectionMethod
     */
    function getMethod( $name );

    /**
     * @return array(\de\buzz2ee\reflection\interfaces\ReflectionMethod)
     */
    function getMethods();

    /**
     * @param string $name
     *
     * @return \de\buzz2ee\reflection\interfaces\ReflectionProperty
     */
    function getProperty( $name );

    /**
     * @return array(\de\buzz2ee\reflection\interfaces\ReflectionProperty)
     */
    function getProperties();
}