<?php
namespace org\pdepend\reflection;

use \org\pdepend\reflection\parser\Parser;
use \org\pdepend\reflection\parser\ParserContext;
use org\pdepend\reflection\interfaces\ReflectionBuilder;

class StaticReflectionBuilder implements ReflectionBuilder
{
    /**
     *
     * @var \org\pdepend\reflection\parser\ParserContext
     */
    private $_context = null;

    public function __construct( ParserContext $context )
    {
        $this->_context = $context;
    }

    public function canBuildClass( $className )
    {
        return true;
    }

    public function buildClass( $className )
    {
        $parser = new Parser( $this->_context, $className );
        return $parser->parse();
    }
}