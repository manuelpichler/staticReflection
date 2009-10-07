<?php
/**
 * I provide completely working code within this article, which will not be
 * developed any further, because there are already existing packages, which try
 * to provide similar functionallities.
 */

namespace com\example\ioc;

require_once 'BaseTest.php';

/**
 * Test case for the default argument validator implementation.
 *
 * @author  Manuel Pichler <mapi@pdepend.org>
 * @license Copyright by Manuel Pichler
 * @version $Revision$
 */
class ReflectionArgumentValidatorTest extends BaseTest
{
    /**
     * @var ReflectionObject
     */
    private $_fixture = null;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_fixture = new \ReflectionObject( $this );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testValidatorAcceptsScalarParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( 42 ) );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testValidatorAcceptsNullAsScalarParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( null ) );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testValidatorAcceptsArrayAsScalarParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( array() ) );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testValidatorAcceptsNullAsArrayParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( null ) );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testValidatorAcceptsArrayAsArrayParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( array() ) );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testValidatorAcceptsTypeAsTypeParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( new \stdClass ) );
    }

    /**
     * @return void
     * @group ioc
     */
    public function testValidatorAcceptsNullAsTypeParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( null ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentNotFoundException
     */
    public function testValidatorThrowsExceptionForMissingParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array() );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentNotFoundException
     */
    public function testValidatorThrowsExceptionForMissingArrayParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array() );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentTypeException
     */
    public function testValidatorThrowsExceptionForInvalidTypeAsArrayParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( 42 ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentTypeException
     */
    public function testValidatorThrowsExceptionForNullAsTypeParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( null ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentTypeException
     */
    public function testValidatorThrowsExceptionForScalarAsTypeParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( 42 ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentTypeException
     */
    public function testValidatorThrowsExceptionForDifferentTypeAsTypeParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( $this ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentTypeException
     */
    public function testValidatorThrowsExceptionForNullAsArrayParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( null ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentTypeException
     */
    public function testValidatorThrowsExceptionForScalarAsArrayParameter()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( 42 ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException com\example\ioc\exceptions\ArgumentTypeException
     */
    public function testValidatorThrowsExceptionForNullAsArrayParameterWithDefault()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( null ) );
    }

    /**
     * @return void
     * @group ioc
     * @expectedException \RuntimeException
     */
    public function testValidatorThrowsRuntimeExceptionForInvalidMethodName()
    {
        $validator = new ReflectionArgumentValidator( __CLASS__ );
        $validator->validate( $this->_getMethodFixtureForTest(), array( null ) );
    }

    /**
     * @return ReflectionMethod
     */
    private function _getMethodFixtureForTest()
    {
        $trace = debug_backtrace();
        return lcfirst( substr( $trace[1]['function'], 4 ) );
    }

    protected function validatorAcceptsScalarParameter( $foo ) {}
    protected function validatorAcceptsNullAsScalarParameter( $foo ) {}
    protected function validatorAcceptsArrayAsScalarParameter( $foo ) {}
    protected function validatorAcceptsArrayAsArrayParameter( array $foo ) {}
    protected function validatorThrowsExceptionForMissingParameter( $foo ) {}
    protected function validatorAcceptsTypeAsTypeParameter( \stdClass $foo ) {}
    protected function validatorAcceptsNullAsArrayParameter( array $foo = null ) {}
    protected function validatorAcceptsNullAsTypeParameter( \stdClass $foo = null ) {}
    protected function validatorThrowsExceptionForNullAsArrayParameter( array $foo ) {}
    protected function validatorThrowsExceptionForMissingArrayParameter( array $foo ) {}
    protected function validatorThrowsExceptionForScalarAsArrayParameter( array $foo ) {}
    protected function validatorThrowsExceptionForNullAsTypeParameter( \stdClass $foo ) {}
    protected function validatorThrowsExceptionForScalarAsTypeParameter( \stdClass $foo ) {}
    protected function validatorThrowsExceptionForInvalidTypeAsArrayParameter( array $foo ) {}
    protected function validatorThrowsExceptionForDifferentTypeAsTypeParameter( \stdClass $foo ) {}
    protected function validatorThrowsExceptionForNullAsArrayParameterWithDefault( array $foo = array() ) {}
}