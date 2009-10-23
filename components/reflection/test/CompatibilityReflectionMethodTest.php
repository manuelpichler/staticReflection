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
class CompatibilityReflectionMethodTest extends BaseCompatibilityTest
{
    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testStaticVersionIsCompatileWithNativeReflection()
    {
        $this->assertPublicApiEquals( 'ReflectionMethod', StaticReflectionMethod::TYPE );
    }
    
    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetName()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertSame( $internal->getName(), $static->getName() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetShortName()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertSame( $internal->getShortName(), $static->getShortName() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetNamespaceName()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertSame( $internal->getNamespaceName(), $static->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetDeclaringClass()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertSame(
            $internal->getDeclaringClass()->getName(),
            $static->getDeclaringClass()->getName()
        );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetFileName()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertSame( $internal->getFileName(), $static->getFileName() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetDocCommentForMethodWithComment()
    {
        $internal = $this->createInternal( 'CompatMethodWithComment', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodWithComment', 'fooBar' );

        $this->assertSame( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetDocCommentForMethodWithoutComment()
    {
        $internal = $this->createInternal( 'CompatMethodWithoutComment', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodWithoutComment', 'fooBar' );

        $this->assertSame( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testIsClosure()
    {
        $internal = $this->createInternal( 'CompatMethodWithoutComment', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodWithoutComment', 'fooBar' );

        $this->assertSame( $internal->isClosure(), $static->isClosure() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testIsDeprecatedForMethodAnnotatedWithDeprecated()
    {
        $internal = $this->createInternal( 'CompatMethodAnnotatedWithDeprecated', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodAnnotatedWithDeprecated', 'fooBar' );

        $this->assertSame( $internal->isDeprecated(), $static->isDeprecated() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testIsDeprecatedForMethodAnnotatedWithoutDeprecated()
    {
        $internal = $this->createInternal( 'CompatMethodAnnotatedWithoutDeprecated', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodAnnotatedWithoutDeprecated', 'fooBar' );

        $this->assertSame( $internal->isDeprecated(), $static->isDeprecated() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testIsConstructorForInterfaceConstructMethod()
    {
        $internal = $this->createInternal( 'CompatInterfaceWithConstruct', '__construct' );
        $static   = $this->createStatic( 'CompatInterfaceWithConstruct', '__construct' );

        $this->assertSame( $internal->isConstructor(), $static->isConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testIsConstructorForInterfaceClassNameMethod()
    {
        $internal = $this->createInternal( 'CompatInterfaceWithClassNameMethod', 'CompatInterfaceWithClassNameMethod' );
        $static   = $this->createStatic( 'CompatInterfaceWithClassNameMethod', 'CompatInterfaceWithClassNameMethod' );

        $this->assertSame( $internal->isConstructor(), $static->isConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testIsDestructorForDestructMethod()
    {
        $internal = $this->createInternal( 'CompatClassWithDestructor', '__destruct' );
        $static   = $this->createStatic( 'CompatClassWithDestructor', '__destruct' );

        $this->assertSame( $internal->isDestructor(), $static->isDestructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testIsDestructorForNonDestructMethod()
    {
        $internal = $this->createInternal( 'CompatClassWithDestructor', 'fooBar' );
        $static   = $this->createStatic( 'CompatClassWithDestructor', 'fooBar' );

        $this->assertSame( $internal->isDestructor(), $static->isDestructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testIsDestructorForAbstractDestructMethod()
    {
        $internal = $this->createInternal( 'CompatAbstractClassWithAbstractDestructor', '__destruct' );
        $static   = $this->createStatic( 'CompatAbstractClassWithAbstractDestructor', '__destruct' );

        $this->assertSame( $internal->isDestructor(), $static->isDestructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testInNamespaceForClassWithNamespace()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertSame( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testInNamespaceForClassWithoutNamespace()
    {
        $internal = $this->createInternal( 'CompatMethodInClassWithoutNamespace', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodInClassWithoutNamespace', 'fooBar' );

        $this->assertSame( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetExtension()
    {
        $internal = $this->createInternal( 'CompatMethodInClassWithoutNamespace', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodInClassWithoutNamespace', 'fooBar' );

        $this->assertSame( $internal->getExtension(), $static->getExtension() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetExtensionName()
    {
        $internal = $this->createInternal( 'CompatMethodInClassWithoutNamespace', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodInClassWithoutNamespace', 'fooBar' );

        $this->assertSame( $internal->getExtensionName(), $static->getExtensionName() );
    }

    /**
     * Creates an internal reflection method instance.
     *
     * @param string $className  Name of the searched class.
     * @param string $methodName Name of the searched method.
     *
     * @return \ReflectionMethod
     */
    protected function createInternal( $className, $methodName )
    {
        return $this->createInternalClass( $className )->getMethod( $methodName );
    }

    /**
     * Creates a static reflection method instance.
     *
     * @param string $className  Name of the searched class.
     * @param string $methodName Name of the searched method.
     *
     * @return \de\buzz2ee\reflection\StaticReflectionMethod
     */
    protected function createStatic( $className, $methodName )
    {
        return $this->createStaticClass( $className )->getMethod( $methodName );
    }
}