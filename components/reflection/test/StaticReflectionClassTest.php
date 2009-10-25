<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection class class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionClassTest extends BaseTest
{
    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetParentClassReturnsFalseWhenNoParentExists()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $this->assertFalse( $class->getParentClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetParentClassReturnsExpectedInstanceWhenParentExists()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $child  = new StaticReflectionClass( __CLASS__, '', 0 );
        $child->initParentClass( $parent );

        $this->assertSame( $parent, $child->getParentClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsExpectedSingleMethod()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( 'fooBar', '', 0 ) ) );

        $this->assertSame( 1, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsExpectedTwoMethods()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods(
            array(
                new StaticReflectionMethod( 'fooBar', '', 0 ),
                new StaticReflectionMethod( 'baz', '', 0 ),
            )
        );

        $this->assertSame( 2, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsFromParentClass()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'bar', '', 0 ) ) );

        $this->assertSame( 2, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsWithSameNameOnlyOneTime()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $this->assertSame( 1, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsWorksCaseInsensitive()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'FoO', '', 0 ) ) );

        $this->assertSame( 1, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsLastMethodInInheritanceHierarchy()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $methods = $class->getMethods();
        $this->assertSame( $class, $methods[0]->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsConcreteMethodInsteadOfAbstract()
    {
        $parent = new StaticReflectionClass( __CLASS__ . 'Parent', '', 0 );
        $parent->initMethods( array( new StaticReflectionMethod( 'foo', '', 0 ) ) );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( $parent );
        $class->initMethods( array( new StaticReflectionMethod( 'foo', '', StaticReflectionMethod::IS_ABSTRACT ) ) );

        $methods = $class->getMethods();
        $this->assertSame( $parent, $methods[0]->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsReturnsMethodsOfImplementedInterface()
    {
        $interface = new \ReflectionClass( 'Iterator' );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initInterfaces( array( $interface ) );

        $this->assertEquals( 5, count( $class->getMethods() ) );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetMethodsPrefersImplementedMethodsOverInterfaceMethods()
    {
        $interface = new \ReflectionClass( 'Iterator' );

        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods(
            array(
                new StaticReflectionMethod( 'current', '', 0 ),
                new StaticReflectionMethod( 'rewind', '', 0 ),
                new StaticReflectionMethod( 'valid', '', 0 ),
            )
        );
        $class->initInterfaces( array( $interface ) );

        $declaredMethodCount = 0;
        foreach ( $class->getMethods() as $method )
        {
            if ( $method->getDeclaringClass()->getName() === $class->getName() )
            {
                ++$declaredMethodCount;
            }
        }
        $this->assertSame( 3, $declaredMethodCount );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testIsAbstractReturnsFalseWhenModifierIsNotSet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $this->assertFalse( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testIsAbstractReturnsTrueWhenIsExplicitAbstractModifierIsSet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', StaticReflectionClass::IS_EXPLICIT_ABSTRACT );
        $this->assertTrue( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testIsAbstractReturnsTrueWhenIsImplicitAbstractModifierIsSet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', StaticReflectionClass::IS_IMPLICIT_ABSTRACT );
        $this->assertTrue( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testIsAbstractReturnsTrueWhenClassContainsAnAbstractMethod()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initMethods( array( new StaticReflectionMethod( 'foo', '', StaticReflectionMethod::IS_ABSTRACT ) ) );
        
        $this->assertTrue( $class->isAbstract() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testIsInterfaceAlwaysReturnsFalse()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $this->assertFalse( $class->isInterface() );
    }

    /**
     * @return void
     * @covers \org\pdepend\reflection\StaticReflectionClass
     * @group reflection
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitParentClassThrowsLogicExceptionWhenParentWasAlreadySet()
    {
        $class = new StaticReflectionClass( __CLASS__, '', 0 );
        $class->initParentClass( new \ReflectionClass( __CLASS__ ) );
        $class->initParentClass( new \ReflectionClass( __CLASS__ ) );
    }
}