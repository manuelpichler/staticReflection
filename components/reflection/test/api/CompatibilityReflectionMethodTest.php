<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace org\pdepend\reflection\api;

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
     * @group reflection::api
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
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetName()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertEquals( $internal->getName(), $static->getName() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetShortName()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertEquals( $internal->getShortName(), $static->getShortName() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetNamespaceName()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertEquals( $internal->getNamespaceName(), $static->getNamespaceName() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetDeclaringClass()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertEquals(
            $internal->getDeclaringClass()->getName(),
            $static->getDeclaringClass()->getName()
        );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetFileName()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertEquals( $internal->getFileName(), $static->getFileName() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetDocCommentForMethodWithComment()
    {
        $internal = $this->createInternal( 'CompatMethodWithComment', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodWithComment', 'fooBar' );

        $this->assertEquals( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetDocCommentForMethodWithoutComment()
    {
        $internal = $this->createInternal( 'CompatMethodWithoutComment', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodWithoutComment', 'fooBar' );

        $this->assertEquals( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsClosure()
    {
        $internal = $this->createInternal( 'CompatMethodWithoutComment', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodWithoutComment', 'fooBar' );

        $this->assertEquals( $internal->isClosure(), $static->isClosure() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsDeprecatedForMethodAnnotatedWithDeprecated()
    {
        $internal = $this->createInternal( 'CompatMethodAnnotatedWithDeprecated', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodAnnotatedWithDeprecated', 'fooBar' );

        $this->assertEquals( $internal->isDeprecated(), $static->isDeprecated() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsDeprecatedForMethodAnnotatedWithoutDeprecated()
    {
        $internal = $this->createInternal( 'CompatMethodAnnotatedWithoutDeprecated', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodAnnotatedWithoutDeprecated', 'fooBar' );

        $this->assertEquals( $internal->isDeprecated(), $static->isDeprecated() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsConstructorForInterfaceConstructMethod()
    {
        $internal = $this->createInternal( 'CompatInterfaceWithConstruct', '__construct' );
        $static   = $this->createStatic( 'CompatInterfaceWithConstruct', '__construct' );

        $this->assertEquals( $internal->isConstructor(), $static->isConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsConstructorForInterfaceClassNameMethod()
    {
        $internal = $this->createInternal( 'CompatInterfaceWithClassNameMethod', 'CompatInterfaceWithClassNameMethod' );
        $static   = $this->createStatic( 'CompatInterfaceWithClassNameMethod', 'CompatInterfaceWithClassNameMethod' );

        $this->assertEquals( $internal->isConstructor(), $static->isConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsConstructorForAbstractConstructMethod()
    {
        $internal = $this->createInternal( 'CompatAbstractConstructMethod', '__construct' );
        $static   = $this->createStatic( 'CompatAbstractConstructMethod', '__construct' );

        $this->assertEquals( $internal->isConstructor(), $static->isConstructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsDestructorForDestructMethod()
    {
        $internal = $this->createInternal( 'CompatClassWithDestructor', '__destruct' );
        $static   = $this->createStatic( 'CompatClassWithDestructor', '__destruct' );

        $this->assertEquals( $internal->isDestructor(), $static->isDestructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsDestructorForNonDestructMethod()
    {
        $internal = $this->createInternal( 'CompatClassWithDestructor', 'fooBar' );
        $static   = $this->createStatic( 'CompatClassWithDestructor', 'fooBar' );

        $this->assertEquals( $internal->isDestructor(), $static->isDestructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsDestructorForAbstractDestructMethod()
    {
        $internal = $this->createInternal( 'CompatAbstractClassWithAbstractDestructor', '__destruct' );
        $static   = $this->createStatic( 'CompatAbstractClassWithAbstractDestructor', '__destruct' );

        $this->assertEquals( $internal->isDestructor(), $static->isDestructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testIsDestructorForInterfaceDestructMethod()
    {
        $internal = $this->createInternal( 'CompatInterfaceWithDestructor', '__destruct' );
        $static   = $this->createStatic( 'CompatInterfaceWithDestructor', '__destruct' );

        $this->assertEquals( $internal->isDestructor(), $static->isDestructor() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testReturnsReferenceClassMethodWithoutBitwiseAnd()
    {
        $internal = $this->createInternal( 'CompatClassMethodWithoutBitwiseAnd', 'fooBar' );
        $static   = $this->createStatic( 'CompatClassMethodWithoutBitwiseAnd', 'fooBar' );

        $this->assertEquals( $internal->returnsReference(), $static->returnsReference() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testReturnsReferenceClassMethodWithBitwiseAnd()
    {
        $internal = $this->createInternal( 'CompatClassMethodWithBitwiseAnd', 'fooBar' );
        $static   = $this->createStatic( 'CompatClassMethodWithBitwiseAnd', 'fooBar' );

        $this->assertEquals( $internal->returnsReference(), $static->returnsReference() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testReturnsReferenceClassAbstractMethodWithBitwiseAnd()
    {
        $internal = $this->createInternal( 'CompatClassAbstractMethodWithBitwiseAnd', 'fooBar' );
        $static   = $this->createStatic( 'CompatClassAbstractMethodWithBitwiseAnd', 'fooBar' );

        $this->assertEquals( $internal->returnsReference(), $static->returnsReference() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testReturnsReferenceInterfaceMethodWithBitwiseAnd()
    {
        $internal = $this->createInternal( 'CompatInterfaceMethodWithBitwiseAnd', 'fooBar' );
        $static   = $this->createStatic( 'CompatInterfaceMethodWithBitwiseAnd', 'fooBar' );

        $this->assertEquals( $internal->returnsReference(), $static->returnsReference() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testInNamespaceForClassWithNamespace()
    {
        $internal = $this->createInternal( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );
        $static   = $this->createStatic( '\compat\CompatMethodInClassWithNamespace', 'fooBar' );

        $this->assertEquals( $internal->inNamespace(), $static->inNamespace() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
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
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetNumberOfRequiredParametersWhereFirstParameterIsMandatory()
    {
        $internal = $this->createInternal( 'CompatParameter', 'fooBar' );
        $static   = $this->createStatic( 'CompatParameter', 'fooBar' );

        $this->assertEquals(
            $internal->getNumberOfRequiredParameters(),
            $static->getNumberOfRequiredParameters()
        );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetNumberOfRequiredParametersWhereSecondParameterIsMandatory()
    {
        $internal = $this->createInternal( 'CompatParametersOptional', 'fooBar' );
        $static   = $this->createStatic( 'CompatParametersOptional', 'fooBar' );

        $this->assertEquals(
            $internal->getNumberOfRequiredParameters(),
            $static->getNumberOfRequiredParameters()
        );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetNumberOfRequiredParametersForMethodWithoutParameters()
    {
        $internal = $this->createInternal( 'CompatMethodWithoutParameters', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodWithoutParameters', 'fooBar' );

        $this->assertEquals(
            $internal->getNumberOfRequiredParameters(),
            $static->getNumberOfRequiredParameters()
        );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetExtension()
    {
        $internal = $this->createInternal( 'CompatMethodInClassWithoutNamespace', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodInClassWithoutNamespace', 'fooBar' );

        $this->assertEquals( $internal->getExtension(), $static->getExtension() );
    }

    /**
     * @return void
     * @covers \ReflectionMethod
     * @group reflection
     * @group reflection::api
     * @group compatibilitytest
     */
    public function testGetExtensionName()
    {
        $internal = $this->createInternal( 'CompatMethodInClassWithoutNamespace', 'fooBar' );
        $static   = $this->createStatic( 'CompatMethodInClassWithoutNamespace', 'fooBar' );

        $this->assertEquals( $internal->getExtensionName(), $static->getExtensionName() );
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
     * @return \org\pdepend\reflection\api\StaticReflectionMethod
     */
    protected function createStatic( $className, $methodName )
    {
        return $this->createStaticClass( $className )->getMethod( $methodName );
    }
}