<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc\interfaces;

use de\buzz2ee\ioc\ObjectBuilderFactory;
use de\buzz2ee\ioc\exceptions\ClassNotFoundException;

/**
 * Abstract base implementation of the object factory interface.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BaseObjectFactory implements ObjectFactory
{
    /**
     * @var ObjectBuilder
     */
    private $_objectBuilder = null;

    /**
     * @var SourceLoader
     */
    private $_sourceLoader = null;

    public function __construct( $className, array $arguments, SourceLoader $sourceLoader )
    {
        $this->_className    = $className;
        $this->_sourceLoader = $sourceLoader;

        $this->_objectBuilder = ObjectBuilderFactory::get()->create( $className );
        
        foreach ( $arguments as $argument )
        {
            $argument->configure( $this );
        }
    }

    /**
     * @param PropertyArgument $argument
     *
     * @return void
     */
    public function registerPropertyArgument( PropertyArgument $argument )
    {
        $this->_objectBuilder->addPropertyArgument( $argument );
    }

    /**
     * @param Argument $argument
     *
     * @return void
     */
    public function registerConstructorArgument( Argument $argument )
    {
        $this->_objectBuilder->addConstructorArgument( $argument );
    }

    /**
     * @return stdClass
     */
    protected function createObject( Container $container )
    {
        $this->_testClassExists();
        return $this->_objectBuilder->build( $container );
    }

    private function _testClassExists()
    {
        if ( $this->_sourceLoader->load( $this->_className ) )
        {
            return;
        }
        throw new ClassNotFoundException( $this->_className );
    }
}