<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\ioc;

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
     */
    protected function setUp()
    {
        parent::setUp();

        ObjectBuilderFactory::set( new ObjectBuilderFactory() );
        ObjectFactoryFactory::set( new ObjectFactoryFactory( new SourceLoaderAutoload() ) );
        ArgumentValidatorFactory::set( new ArgumentValidatorFactory() );
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        ObjectFactoryFactory::set( null );
        ArgumentValidatorFactory::set( null );
        ObjectBuilderFactory::set( null );

        parent::tearDown();
    }

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
    public function testContainerLookupReturnsSameSingletonInstanceOfStdClass()
    {
        $container = $this->createContainer();
        $container->registerSingleton( __FUNCTION__, '\stdClass' );

        $object1 = $container->lookup( __FUNCTION__ );
        $object2 = $container->lookup( __FUNCTION__ );

        $this->assertSame( $object1, $object2 );
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
     */
    public function testContainerAcceptsNullConstructorArgument()
    {
        $arguments = array( new ConstructorValueArgument( null ) );
        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Chapter::TYPE, $arguments );
        
        $chapter = $container->lookup( __FUNCTION__ );
        $this->assertNull( $chapter->getTitle() );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerInjectsBySetterArgument()
    {
        $arguments = array(
            new ConstructorValueArgument( __METHOD__ ),
            new PropertyValueArgument( 'pages', 42 )
        );

        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Chapter::TYPE, $arguments );

        $chapter = $container->lookup( __FUNCTION__ );
        $this->assertSame( 42, $chapter->getPages() );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testContainerInjectsByPropertyArgument()
    {
        $arguments = array(
            new ConstructorValueArgument( __METHOD__ ),
            new PropertyValueArgument( 'subtitle', 'Dependency Injection' )
        );

        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Chapter::TYPE, $arguments );

        $chapter = $container->lookup( __FUNCTION__ );
        $this->assertSame( 'Dependency Injection', $chapter->subtitle );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException de\buzz2ee\ioc\exceptions\PropertyNotFoundException
     */
    public function testContainerThrowsExceptionForInjectionOfAnUnknownProperty()
    {
        $arguments = array(
            new ConstructorValueArgument( __METHOD__ ),
            new PropertyValueArgument( __FUNCTION__, 42 )
        );
        
        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Chapter::TYPE, $arguments );

        $container->lookup( __FUNCTION__ );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException \LogicException
     */
    public function testContainerThrowsExceptionForMissingMandatoryConstructorArgument()
    {
        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, Chapter::TYPE );
        $container->lookup( __FUNCTION__ );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException de\buzz2ee\ioc\exceptions\CyclicDependencyException
     */
    public function testContainerThrowsExceptionForCyclicConstructorDependency()
    {
        $arguments = array( new ConstructorReferenceArgument( __FUNCTION__ ) );
        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, CtorCycle::TYPE, $arguments );

        $container->lookup( __FUNCTION__ );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException de\buzz2ee\ioc\exceptions\ClassNotFoundException
     */
    public function testContainerThrowsExceptionForUnknownClass()
    {
        $container = $this->createContainer();
        $container->registerPrototype( __FUNCTION__, __FUNCTION__ );

        $container->lookup( __FUNCTION__ );
    }

    /**
     * @return Container
     */
    protected abstract function createContainer();
}