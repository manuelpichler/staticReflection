<?php
namespace org\pdepend\reflection\interfaces;

interface ParserContext
{
    /**
     * This method will be called by the reflection parser for all found classes
     * and/or interfaces that the currently parsed class depends on.
     *
     * @param string $className Full qualified name of the request class.
     *
     * @return \ReflectionClass
     */
    function getClass( $className );
}