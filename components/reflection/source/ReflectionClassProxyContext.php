<?php
namespace org\pdepend\reflection;

use org\pdepend\reflection\interfaces\ParserContext;

class ReflectionClassProxyContext implements ParserContext
{
    private $_session = null;

    public function __construct( ReflectionSession $session )
    {
        $this->_session = $session;
    }

    /**
     *
     * @param string $className
     *
     * @return \ReflectionClass
     */
    public function getClass( $className )
    {
        return new ReflectionClassProxy( $this->_session, $className );
    }
}