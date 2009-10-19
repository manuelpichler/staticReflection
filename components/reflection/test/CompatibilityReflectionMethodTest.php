<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace de\buzz2ee\reflection;

require_once 'BaseTest.php';

/**
 * Tests the api compatiblility between the static reflection implementation and
 * PHP's native api.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class CompatibilityReflectionMethodTest extends BaseTest
{
    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
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
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
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
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
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
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
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
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
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
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetDocCommentForMethodWithComment()
    {
        $internal = $this->createInternal( 'CompatMethodWithComment', 'fooBar' );
        $static   = $this->createInternal( 'CompatMethodWithComment', 'fooBar' );

        $this->assertSame( $internal->getDocComment(), $static->getDocComment() );
    }

    /**
     * @return void
     * @covers \de\buzz2ee\reflection\StaticReflectionMethod
     * @group reflection
     * @group compatibilitytest
     */
    public function testGetDocCommentForMethodWithoutComment()
    {
        $internal = $this->createInternal( 'CompatMethodWithoutComment', 'fooBar' );
        $static   = $this->createInternal( 'CompatMethodWithoutComment', 'fooBar' );

        $this->assertSame( $internal->getDocComment(), $static->getDocComment() );
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
     * Creates an internal reflection class instance.
     *
     * @param string $className Name of the searched class.
     *
     * @return \ReflectionClass
     */
    protected function createInternalClass( $className )
    {
        $this->includeClass( $className );
        return new \ReflectionClass( $className );
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

    /**
     * Creates an internal reflection class instance.
     *
     * @param string $className Name of the searched class.
     *
     * @return \ReflectionClass
     */
    protected function createStaticClass( $className )
    {
        $parser = new parser\Parser( $this->createSourceResolver(), $className );
        return $parser->parse();
    }
}