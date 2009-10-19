<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

require_once 'BaseTest.php';

/**
 * Test cases for the reflection method class.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class StaticReflectionMethodTest extends BaseTest
{
    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testGetDocCommentReturnsFalseWhenEmptyDocCommentWasGiven()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->getDocComment() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testGetDocCommentReturnsDocCommentWhenDocCommentContainsText()
    {
        $method = new StaticReflectionMethod( 'foo', '/** @return void */', 0 );
        $this->assertSame( '/** @return void */', $method->getDocComment() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testGetFileNameReturnsFileOfDeclaringClass()
    {
        $class  = new \ReflectionClass( __CLASS__ );
        $method = new StaticReflectionMethod( 'foo', '/** @return void */', 0 );
        $method->initDeclaringClass( $class );

        $this->assertSame( $class->getFileName(), $method->getFileName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsAbstractReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isAbstract() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsAbstractReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_ABSTRACT );
        $this->assertTrue( $method->isAbstract() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsFinalReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isFinal() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsFinalReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_FINAL );
        $this->assertTrue( $method->isFinal() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsPrivateReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isPrivate() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsPrivateReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PRIVATE );
        $this->assertTrue( $method->isPrivate() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsProtectedReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isProtected() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsProtectedReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PROTECTED );
        $this->assertTrue( $method->isProtected() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsPublicReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isPublic() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsPublicReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_PUBLIC );
        $this->assertTrue( $method->isPublic() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsStaticReturnsFalseWhenModifierWasNotSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isStatic() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsStaticReturnsTrueWhenModifierWasSupplied()
    {
        $method = new StaticReflectionMethod( 'foo', '', \ReflectionMethod::IS_STATIC );
        $this->assertTrue( $method->isStatic() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsInternalAlwaysReturnsFalse()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertFalse( $method->isInternal() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testIsUserDefinedAlwaysReturnsTrue()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertTrue( $method->isUserDefined() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testGetShortNameIsIdenticalWithGetName()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertSame( $method->getName(), $method->getShortName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testGetNamespaceNameIsAlwaysAnEmptyString()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertSame( '', $method->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testGetDeclaringClassReturnsNullByDefault()
    {
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $this->assertNull( $method->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     */
    public function testGetDeclaringClassReturnsPreviousSetClassInstance()
    {
        $class  = new \ReflectionClass( __CLASS__ );
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initDeclaringClass( $class );

        $this->assertSame( $class, $method->getDeclaringClass() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     * @expectedException \ReflectionException
     */
    public function testConstructorThrowsExceptionWhenInvalidModifierWasGiven()
    {
        $method = new StaticReflectionMethod( 'foo', '', 42 );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group unittest
     * @expectedException \LogicException
     */
    public function testInitDeclaringClassThrowsLogicExceptionWhenDeclaringClassWasAlreadySet()
    {
        $class  = new \ReflectionClass( __CLASS__ );
        $method = new StaticReflectionMethod( 'foo', '', 0 );
        $method->initDeclaringClass( $class );
        $method->initDeclaringClass( $class );
    }
}