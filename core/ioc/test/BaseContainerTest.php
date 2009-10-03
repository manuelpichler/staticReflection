<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

require_once 'BaseTest.php';

/**
 * Common test case for the container interface contract.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
abstract class BaseContainerTest extends BaseTest
{
    /**
     * @return void
     * @group ioc
     */
    public function testContainerLookupReturnsSingletonInstance()
    {
        $container = $this->createContainer();
        $container->registerSingleton( __FUNCTION__, Author::TYPE );

        $author = $container->lookup( __FUNCTION__ );
        $this->assertType( Author::TYPE, $author );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerLookupReturnsSameSingletonInstances()
    {
        $container = $this->createContainer();
        $container->registerSingleton( __FUNCTION__, Author::TYPE );

        $author1 = $container->lookup( __FUNCTION__ );
        $author2 = $container->lookup( __FUNCTION__ );

        $this->assertSame( $author1, $author2 );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerLookupReturnsPrototypeInstance()
    {
        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Author::TYPE );

        $author = $container->lookup( __FUNCTION__ );
        $this->assertType( Author::TYPE, $author );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerLookupReturnsMultiplePrototypeInstances()
    {
        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Author::TYPE );

        $author1 = $container->lookup( __FUNCTION__ );
        $author2 = $container->lookup( __FUNCTION__ );

        $this->assertNotSame( $author1, $author2 );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerLookupInjectsSingleScalarConstructorArgument()
    {
        $arguments = array( new ConstructorValueArgument( 'Manuel' ) );

        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Author::TYPE, $arguments );

        $author = $container->lookup( __FUNCTION__ );
        $this->assertSame( 'Manuel', $author->getFirstName() );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerLookupInjectsMultipleScalarConstructorArguments()
    {
        $arguments = array(
            new ConstructorValueArgument( 'Manuel' ),
            new ConstructorValueArgument( 'Pichler' )
        );

        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Author::TYPE, $arguments );

        $author = $container->lookup( __FUNCTION__ );
        $this->assertSame(
            Author::TYPE . '[firstName=Manuel; lastName=Pichler]',
            (string) $author
        );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerLookupInjectsReferenceConstructorArgument()
    {
        $arguments = array( new ConstructorReferenceArgument( Author::TYPE ) );

        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Book::TYPE, $arguments );
        $container->registerSingleton( Author::TYPE, Author::TYPE );

        $book = $container->lookup( __FUNCTION__ );
        $this->assertType( Author::TYPE, $book->getAuthor() );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerLookupInjectsTheSameReferenceConstructorArgument()
    {
        $arguments = array( new ConstructorReferenceArgument( Author::TYPE ) );

        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Book::TYPE, $arguments );
        $container->registerSingleton( Author::TYPE, Author::TYPE );

        $book1 = $container->lookup( __FUNCTION__ );
        $book2 = $container->lookup( __FUNCTION__ );

        $this->assertSame( $book1->getAuthor(), $book2->getAuthor() );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException \RuntimeException
     */
    public function testContainerThrowsExceptionForUnknownLookupKey()
    {
        $container = $this->createContainer();
        $container->lookup( __FUNCTION__ );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException \LogicException
     */
    public function testContainerThrowsExceptionForCyclicConstructorDependency()
    {
        $arguments = array( new ConstructorReferenceArgument( __FUNCTION__ ) );
        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, CtorCycle::TYPE, $arguments );

        $container->lookup( __FUNCTION__ );
    }

    /**
     * @return Container
     */
    protected abstract function createContainer();
}