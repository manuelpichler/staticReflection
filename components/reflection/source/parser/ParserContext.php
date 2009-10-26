<?php
namespace org\pdepend\reflection\parser;

use \org\pdepend\reflection\ReflectionSession;
use \org\pdepend\reflection\interfaces\SourceResolver;

class ParserContext
{
    /**
     * The currently used reflection session instance.
     *
     * @var \org\pdepend\reflection\ReflectionSession
     */
    private $_session = null;

    /**
     * The configured source resolver.
     *
     * @var \org\pdepend\reflection\interfaces\SourceResolver
     */
    private $_resolver = null;

    /**
     * Constructs a new parser context.
     *
     * @param ReflectionSession $session  The currently used reflection session.
     * @param SourceResolver    $resolver The static source resolver.
     */
    public function __construct( ReflectionSession $session, SourceResolver $resolver )
    {
        $this->_session  = $session;
        $this->_resolver = $resolver;
    }

    /**
     * Returns a reflection instance for the given class/interface name.
     *
     * @param string $className Name of the searched class/interface.
     *
     * @return \ReflectionClass
     */
    public function getClass( $className )
    {
        return $this->_session->getClass( $className );
    }

    /**
     * Returns the source code for the given class/interface.
     *
     * @param string $className Name of the currently searched class/interface.
     *
     * @return string
     */
    public function getSource( $className )
    {
        return $this->_resolver->getSourceForClass( $className );
    }

    /**
     * Returns the pathname of source file for the given class/interface.
     *
     * @param string $className Name of the currently searched class/interface.
     *
     * @return string
     */
    public function getPathname( $className )
    {
        return $this->_resolver->getPathnameForClass( $className );
    }
}