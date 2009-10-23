<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

require_once 'BaseCompatibilityTest.php';

/**
 * Tests the api compatiblility between the static reflection implementation and
 * PHP's native api.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class CompatibilityReflectionInterfaceTest extends BaseCompatibilityTest
{
    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionClass', StaticReflectionInterface::TYPE );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetDocCommentForInterfaceWithDocComment()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithDocComment' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithDocComment' );

        $this->assertSame( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testGetDocCommentForInterfaceWithoutDocComment()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithoutDocComment' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithoutDocComment' );

        $this->assertSame( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testInNamespaceForInterfaceWithNamespace()
    {
        $internal = $this->createInternalClass( '\foo\bar\CompatInterfaceWithNamespace' );
        $static   = $this->createStaticClass( '\foo\bar\CompatInterfaceWithNamespace' );

        $this->assertSame( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testInNamespaceForInterfaceWithNamespaceDefault()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithNamespaceDefault' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithNamespaceDefault' );

        $this->assertSame( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testInNamespaceForInterfaceWithoutNamespace()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithoutNamespace' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithoutNamespace' );

        $this->assertSame( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testHasConstantThatExists()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithConstant' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithConstant' );

        $this->assertSame( $internal->hasConstant( 'T_BAR' ), $static->hasConstant( 'T_BAR' ) );
    }

    /**
     * @return void
     * @covers \ReflectionClass
     * @group reflection
     * @group unittest
     */
    public function testHasConstantThatDoesNotExists()
    {
        $internal = $this->createInternalClass( 'CompatInterfaceWithConstant' );
        $static   = $this->createStaticClass( 'CompatInterfaceWithConstant' );

        $this->assertSame( $internal->hasConstant( 'T_FOO' ), $static->hasConstant( 'T_FOO' ) );
    }
}